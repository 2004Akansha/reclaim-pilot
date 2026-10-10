<?php

namespace App\Jobs;

use App\Models\RecoveryCase;
use App\Services\CaseAnalysisService;
use App\Services\ScreenshotAnalysisService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class AnalyzeCaseJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 2;

    public int $timeout = 180;

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
        CaseAnalysisService $analysisService
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

                Log::error(
                    'Screenshot analysis failed.',
                    [
                        'case_id' => $this->case->id,
                        'screenshot_id' => $screenshot->id,
                        'error' => $e->getMessage(),
                    ]
                );

                throw $e;
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

        $analysisData =
            $analysisService->analyze(
                $this->case
            );


        /*
        |--------------------------------------------------------------------------
        | Stage 4 — Checking policies
        |--------------------------------------------------------------------------
        |
        | At the moment this is part of the case analysis result.
        | Later this can become a separate service.
        |--------------------------------------------------------------------------
        */

        $this->updateProgress(
            65,
            'checking_policies'
        );


        /*
        |--------------------------------------------------------------------------
        | Stage 5 — Finding similar cases
        |--------------------------------------------------------------------------
        |
        | Later this can use your database / embeddings / AI.
        |--------------------------------------------------------------------------
        */

        $this->updateProgress(
            90,
            'finding_similar_cases'
        );


        /*
        |--------------------------------------------------------------------------
        | Save final analysis
        |--------------------------------------------------------------------------
        */

        $this->case->analyses()->create([

            'issue_detected' =>
                $analysisData['issue_detected'] ?? null,

            'possible_reason' =>
                $analysisData['possible_reason'] ?? null,

            'confidence' =>
                $analysisData['confidence'] ?? 0,

            'summary' =>
                $analysisData['summary'] ?? null,

            'recommended_action' =>
                $analysisData['recommended_action'] ?? null,

        ]);


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
                'case_id' => $this->case->id,
            ]
        );
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
            'case_id' => $this->case->id,
            'error' => $exception->getMessage(),
            'exception' => get_class($exception),
        ]
    );

    $this->case->update([
        'status' => 'analysis_failed',
        'analysis_progress' => 0,
        'analysis_step' => 'failed',
    ]);
}

}