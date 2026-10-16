<?php

namespace App\Services;

use App\Models\CaseScreenshot;
use App\Services\AI\AIClient;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class ScreenshotAnalysisService
{
    public function __construct(
        protected AIClient $aiClient
    ) {
    }

    /**
     * Analyze a screenshot using vision AI.
     *
     * If Ollama/Vision AI is unavailable, return a safe
     * fallback result instead of failing the entire queue job.
     */
    public function analyze(
        CaseScreenshot $screenshot
    ): array {
        /*
        |--------------------------------------------------------------------------
        | Check file extension
        |--------------------------------------------------------------------------
        */

        $extension = strtolower(
            pathinfo(
                $screenshot->file_path,
                PATHINFO_EXTENSION
            )
        );

        if (!in_array(
            $extension,
            ['jpg', 'jpeg', 'png', 'webp'],
            true
        )) {
            return [
                'detected_issue' => 'unknown',
                'visible_message' => null,
                'possible_reason' => null,
                'confidence' => 0,
                'raw_analysis' => [
                    'source' => 'rule_based',
                    'reason' => 'Unsupported image format for vision analysis.',
                ],
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Check whether file exists
        |--------------------------------------------------------------------------
        */

        if (!Storage::disk('public')->exists(
            $screenshot->file_path
        )) {
            return [
                'detected_issue' => 'unknown',
                'visible_message' => null,
                'possible_reason' => null,
                'confidence' => 0,
                'raw_analysis' => [
                    'source' => 'rule_based',
                    'reason' => 'Screenshot file does not exist.',
                ],
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Read image
        |--------------------------------------------------------------------------
        */

        try {
            $contents = Storage::disk('public')
                ->get($screenshot->file_path);
        } catch (\Throwable $e) {

            Log::warning(
                'Could not read screenshot. Continuing without vision analysis.',
                [
                    'screenshot_id' => $screenshot->id,
                    'error' => $e->getMessage(),
                ]
            );

            return [
                'detected_issue' => 'unknown',
                'visible_message' => null,
                'possible_reason' => null,
                'confidence' => 0,
                'raw_analysis' => [
                    'source' => 'rule_based',
                    'reason' => 'Unable to read screenshot file.',
                ],
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Convert image to Base64
        |--------------------------------------------------------------------------
        */

        $base64 = base64_encode($contents);

        /*
        |--------------------------------------------------------------------------
        | Vision AI prompts
        |--------------------------------------------------------------------------
        */

        $systemPrompt = <<<'PROMPT'
You are an account recovery screenshot analysis assistant.

Analyze only information that is actually visible in the screenshot.

Do not invent information.

Do not guess hidden account details.

Return ONLY valid JSON.

The JSON must have exactly these fields:

{
    "detected_issue": "account_disabled|hacked_account|cant_login|verification_required|other|unknown",
    "visible_message": "string",
    "possible_reason": "string|null",
    "confidence": 0
}

The confidence value must be between 0 and 100.
PROMPT;

        $userPrompt = <<<'PROMPT'
Analyze this account recovery screenshot.

Determine whether the visible screen indicates:

- account disabled
- hacked or compromised account
- login failure
- verification request
- another account problem
- unknown

Extract the important visible message.

Do not infer information that cannot be seen.

Return ONLY JSON.
PROMPT;

        /*
        |--------------------------------------------------------------------------
        | Try Vision AI
        |--------------------------------------------------------------------------
        */

        try {

            Log::info(
                'Attempting screenshot vision analysis.',
                [
                    'screenshot_id' => $screenshot->id,
                ]
            );

            $response = $this->aiClient->generateWithImages(
                $systemPrompt,
                $userPrompt,
                [$base64]
            );

            /*
            |--------------------------------------------------------------------------
            | Decode JSON
            |--------------------------------------------------------------------------
            */

            $analysis = json_decode(
                $response,
                true
            );

            if (
                json_last_error() !== JSON_ERROR_NONE ||
                !is_array($analysis)
            ) {
                throw new RuntimeException(
                    'Vision AI returned invalid JSON.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Normalize AI result
            |--------------------------------------------------------------------------
            */

            return [
                'detected_issue' =>
                    $analysis['detected_issue'] ?? 'unknown',

                'visible_message' =>
                    $analysis['visible_message'] ?? null,

                'possible_reason' =>
                    $analysis['possible_reason'] ?? null,

                'confidence' =>
                    max(
                        0,
                        min(
                            100,
                            (int) (
                                $analysis['confidence'] ?? 0
                            )
                        )
                    ),

                'raw_analysis' => $analysis,
            ];

        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | IMPORTANT
            |--------------------------------------------------------------------------
            |
            | Ollama/Vision AI failed.
            |
            | Do NOT throw the exception.
            |
            | Returning a fallback result allows AnalyzeCaseJob
            | to continue to the normal case analysis.
            |
            */

            Log::warning(
                'Screenshot vision AI unavailable. Continuing with fallback.',
                [
                    'screenshot_id' => $screenshot->id,
                    'error' => $e->getMessage(),
                ]
            );

            return [
                'detected_issue' => 'unknown',

                'visible_message' => null,

                'possible_reason' => null,

                'confidence' => 0,

                'raw_analysis' => [
                    'source' => 'rule_based',
                    'reason' => 'Vision AI unavailable.',
                    'error' => $e->getMessage(),
                ],
            ];
        }
    }
}