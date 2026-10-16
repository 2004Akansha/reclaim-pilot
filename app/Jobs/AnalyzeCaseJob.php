<?php

namespace App\Jobs;

use App\Models\RecoveryCase;
use App\Services\ScreenshotAnalysisService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\AI\AIAnalysisService;
use App\Services\AI\GuidanceAIService;
use Illuminate\Support\Facades\Log;
use Throwable;

class AnalyzeCaseJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Number of attempts.
     */
    public int $tries = 2;

    /**
     * Maximum execution time.
     */
    public int $timeout = 180;

    /**
     * Wait before retrying failed job.
     */
    public int $backoff = 10;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public RecoveryCase $case
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(
        ScreenshotAnalysisService $screenshotService,
        AIAnalysisService $aiAnalysisService,
        GuidanceAIService $guidanceAIService
    ): void {

        Log::info('Starting recovery case analysis.', [
            'case_id' => $this->case->id,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Stage 1 — Reading inputs
        |--------------------------------------------------------------------------
        */

        $this->updateProgress(
            10,
            'reading_inputs'
        );

        $this->case->load([
            'screenshots.analysis',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Stage 2 — Analyze screenshots
        |--------------------------------------------------------------------------
        */

        $this->updateProgress(
            20,
            'analyzing_screenshots'
        );

        $screenshots = $this->case->screenshots;

        $imageScreenshots = $screenshots->filter(function ($screenshot) {

            $extension = strtolower(
                pathinfo(
                    $screenshot->file_path,
                    PATHINFO_EXTENSION
                )
            );

            return in_array(
                $extension,
                [
                    'jpg',
                    'jpeg',
                    'png',
                    'webp',
                ],
                true
            );
        });

        $totalScreenshots = $imageScreenshots->count();

        $processedScreenshots = 0;

        foreach ($imageScreenshots as $screenshot) {

            /*
            |--------------------------------------------------------------------------
            | Don't analyze same screenshot twice
            |--------------------------------------------------------------------------
            */

            if ($screenshot->analysis) {

                $processedScreenshots++;

                continue;
            }

            try {

                $result = $screenshotService->analyze(
                    $screenshot
                );

                /*
                |--------------------------------------------------------------------------
                | Save screenshot analysis
                |--------------------------------------------------------------------------
                */

                $screenshot->analysis()->create([

                    'detected_issue' =>
                        $result['detected_issue'] ?? null,

                    'visible_message' =>
                        $result['visible_message'] ?? null,

                    'possible_reason' =>
                        $result['possible_reason'] ?? null,

                    'confidence' =>
                        $result['confidence'] ?? 0,

                    'raw_analysis' =>
                        $result['raw_analysis'] ?? null,

                ]);

            } catch (Throwable $e) {

    Log::warning(
        'Screenshot analysis failed. Continuing without screenshot AI analysis.',
        [
            'case_id' => $this->case->id,
            'screenshot_id' => $screenshot->id,
            'error' => $e->getMessage(),
        ]
    );

    /*
    |--------------------------------------------------------------------------
    | Do not stop the entire case analysis.
    |--------------------------------------------------------------------------
    */

    $result = [
        'detected_issue' => 'unknown',
        'visible_message' => null,
        'possible_reason' => null,
        'confidence' => 0,
        'raw_analysis' => [
            'source' => 'rule_based',
            'reason' => 'Screenshot vision analysis unavailable.',
        ],
    ];

    $screenshot->analysis()->create([
        'detected_issue' =>
            $result['detected_issue'],

        'visible_message' =>
            $result['visible_message'],

        'possible_reason' =>
            $result['possible_reason'],

        'confidence' =>
            $result['confidence'],

        'raw_analysis' =>
            $result['raw_analysis'],
    ]);
}

            $processedScreenshots++;

            /*
            |--------------------------------------------------------------------------
            | Update screenshot progress
            |--------------------------------------------------------------------------
            */

            if ($totalScreenshots > 0) {

                $percentage = (
                    $processedScreenshots
                    / $totalScreenshots
                );

                $progress = (int) (
                    20 + ($percentage * 25)
                );

                $this->updateProgress(
                    $progress,
                    'analyzing_screenshots'
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Stage 3 — Identify issue
        |--------------------------------------------------------------------------
        */

        $this->updateProgress(
            50,
            'identifying_issue'
        );

        /*
        |--------------------------------------------------------------------------
        | Reload screenshot analyses
        |--------------------------------------------------------------------------
        */

        $this->case->load([
            'screenshots.analysis',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Run final case analysis
        |--------------------------------------------------------------------------
        */

        Log::info(
            'Running AI case analysis.',
            [
                'case_id' => $this->case->id,
            ]
        );

        $analysisData = $aiAnalysisService->analyze(
            $this->case
        );

        /*
        |--------------------------------------------------------------------------
        | Save final analysis
        |--------------------------------------------------------------------------
        */

        $analysis = $this->case->analyses()->create([

            /*
            |--------------------------------------------------------------------------
            | Keep user's selected issue type
            |--------------------------------------------------------------------------
            */

            'issue_detected' =>
                $this->getIssueName(
                    $this->case->issue_type
                ),

            /*
            |--------------------------------------------------------------------------
            | AI-generated analysis fields
            |--------------------------------------------------------------------------
            */

            'possible_reason' =>
                $analysisData['possible_reason'] ?? null,

            'confidence' =>
                $analysisData['confidence'] ?? 0,

            'summary' =>
                $analysisData['summary'] ?? null,

            'recommended_action' =>
                $analysisData['recommended_action'] ?? null,

            /*
            |--------------------------------------------------------------------------
            | Guidance will be generated next
            |--------------------------------------------------------------------------
            */

            'guidance' => null,

            /*
            |--------------------------------------------------------------------------
            | Guidance source will be saved after generation
            |--------------------------------------------------------------------------
            */

            'guidance_source' => null,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Stage 4 — Generate recovery guidance
        |--------------------------------------------------------------------------
        */

        $this->updateProgress(
            80,
            'generating_guidance'
        );

        Log::info(
            'Generating recovery guidance.',
            [
                'case_id' =>
                    $this->case->id,

                'analysis_id' =>
                    $analysis->id,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Guidance AI service
        |--------------------------------------------------------------------------
        |
        | This service tries Ollama first.
        |
        | If Ollama works:
        |
        |     source = ai
        |
        | If Ollama fails:
        |
        |     source = rule_based
        |
        */

        $guidanceResult = $guidanceAIService->generate(
            $this->case,
            $analysis
        );

        /*
        |--------------------------------------------------------------------------
        | Validate guidance result
        |--------------------------------------------------------------------------
        */

        $guidanceSource = $guidanceResult['source']
            ?? 'rule_based';

        $guidance = $guidanceResult['guidance']
            ?? [];

        /*
        |--------------------------------------------------------------------------
        | Safety check
        |--------------------------------------------------------------------------
        |
        | Only these two values are allowed.
        |
        */

        if (!in_array(
            $guidanceSource,
            [
                'ai',
                'rule_based',
            ],
            true
        )) {

            Log::warning(
                'Unknown guidance source returned. Using rule-based source.',
                [
                    'case_id' => $this->case->id,
                    'analysis_id' => $analysis->id,
                    'source' => $guidanceSource,
                ]
            );

            $guidanceSource = 'rule_based';
        }

        /*
        |--------------------------------------------------------------------------
        | Log actual guidance source
        |--------------------------------------------------------------------------
        */

        Log::info(
            'Recovery guidance generated.',
            [
                'case_id' =>
                    $this->case->id,

                'analysis_id' =>
                    $analysis->id,

                'source' =>
                    $guidanceSource,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Save guidance + source
        |--------------------------------------------------------------------------
        */

        $analysis->update([

            'guidance' =>
                $guidance,

            'guidance_source' =>
                $guidanceSource,

        ]);

        /*
        |--------------------------------------------------------------------------
        | Stage 5 — Finalize recommendation
        |--------------------------------------------------------------------------
        */

        $this->updateProgress(
            95,
            'finalizing'
        );

        /*
        |--------------------------------------------------------------------------
        | Update recovery case
        |--------------------------------------------------------------------------
        */

        $this->case->update([

            'status' =>
                'analyzed',

            'possible_reason' =>
                $analysisData['possible_reason'] ?? null,

            'analysis_progress' =>
                100,

            'analysis_step' =>
                'completed',

        ]);

        /*
        |--------------------------------------------------------------------------
        | Completed
        |--------------------------------------------------------------------------
        */

        Log::info(
            'Recovery case analysis completed.',
            [
                'case_id' =>
                    $this->case->id,

                'analysis_id' =>
                    $analysis->id,

                'guidance_source' =>
                    $guidanceSource,
            ]
        );
    }

    /**
     * Convert database issue type to a human-readable name.
     */
    protected function getIssueName(string $issueType): string
    {
        return match ($issueType) {

            'account_disabled' =>
                'Account disabled',

            'hacked_account' =>
                'Possible hacked account',

            'cant_login' =>
                "Can't log in",

            default =>
                'Other account issue',
        };
    }

    /**
     * Update analysis progress.
     */
    private function updateProgress(
        int $progress,
        string $step
    ): void {

        $this->case->update([

            'status' =>
                'analyzing',

            'analysis_progress' =>
                $progress,

            'analysis_step' =>
                $step,

        ]);
    }

    /**
     * Handle permanent failure.
     */
    public function failed(
        Throwable $exception
    ): void {

        Log::error(
            'Recovery case analysis permanently failed.',
            [
                'case_id' =>
                    $this->case->id,

                'error' =>
                    $exception->getMessage(),

                'exception' =>
                    get_class($exception),
            ]
        );

        $this->case->update([

            'status' =>
                'analysis_failed',

            'analysis_progress' =>
                0,

            'analysis_step' =>
                'failed',

        ]);
    }
}