<?php

namespace App\Services;

use App\Models\CaseScreenshot;
use App\Services\AI\AIClient;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class ScreenshotAnalysisService
{
    public function __construct(
        protected AIClient $aiClient
    ) {
    }

    public function analyze(
        CaseScreenshot $screenshot
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Only image files should reach vision AI
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
            throw new RuntimeException(
                'This screenshot format is not supported by vision analysis.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Read image
        |--------------------------------------------------------------------------
        */

        if (!Storage::disk('public')->exists(
            $screenshot->file_path
        )) {
            throw new RuntimeException(
                'Screenshot file does not exist.'
            );
        }

        $contents = Storage::disk('public')
            ->get($screenshot->file_path);


        /*
        |--------------------------------------------------------------------------
        | Convert image to base64
        |--------------------------------------------------------------------------
        */

        $base64 = base64_encode(
            $contents
        );


        /*
        |--------------------------------------------------------------------------
        | Vision AI prompt
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
        | Call Ollama Vision AI
        |--------------------------------------------------------------------------
        */

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


        if (!is_array($analysis)) {
            throw new RuntimeException(
                'Vision AI returned invalid JSON.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Normalize result
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
    }
}