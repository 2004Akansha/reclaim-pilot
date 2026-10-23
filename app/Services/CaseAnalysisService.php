<?php

namespace App\Services;

use App\Models\RecoveryCase;
use App\Services\AI\AIClient;
use Illuminate\Support\Facades\Validator;
use RuntimeException;

class CaseAnalysisService
{
    public function __construct(
        protected AIClient $aiClient
    ) {
    }

    /**
     * Analyze the complete recovery case.
     *
     * This combines:
     *
     * 1. User-provided information
     * 2. Screenshot / Vision AI analysis
     *
     * and sends both to the text AI for final analysis.
     */
    public function analyze(RecoveryCase $case): array
    {
        /*
        |--------------------------------------------------------------------------
        | Load screenshot analyses
        |--------------------------------------------------------------------------
        |
        | We explicitly load:
        |
        | RecoveryCase
        |     -> screenshots
        |          -> analysis
        |
        */

        $case->loadMissing([
            'screenshots.analysis',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Collect screenshot evidence
        |--------------------------------------------------------------------------
        */

        $screenshotEvidence = $case->screenshots
            ->map(function ($screenshot) {

                /*
                | No Vision AI analysis for this screenshot.
                */

                if (!$screenshot->analysis) {
                    return null;
                }

                return [
                    'filename' =>
                        $screenshot->original_name,

                    'detected_issue' =>
                        $screenshot->analysis->detected_issue,

                    'visible_message' =>
                        $screenshot->analysis->visible_message,

                    'possible_reason' =>
                        $screenshot->analysis->possible_reason,

                    'confidence' =>
                        $screenshot->analysis->confidence,
                ];
            })
            ->filter()
            ->values()
            ->all();


        /*
        |--------------------------------------------------------------------------
        | Build complete case information
        |--------------------------------------------------------------------------
        */

        $caseInformation = [

            /*
            | Information entered by the user
            */

            'issue_type' =>
                $case->issue_type,

            'description' =>
                $case->description,

            'username' =>
                $case->username,

            'email' =>
                $case->email,

            'account_type' =>
                $case->account_type,

            /*
            | Information extracted from screenshots
            */

            'screenshot_evidence' =>
                $screenshotEvidence,
        ];


        /*
        |--------------------------------------------------------------------------
        | Convert case information to JSON
        |--------------------------------------------------------------------------
        */

        try {

            $caseJson = json_encode(
                $caseInformation,
                JSON_PRETTY_PRINT |
                JSON_UNESCAPED_SLASHES |
                JSON_THROW_ON_ERROR
            );

        } catch (\JsonException $e) {

            throw new RuntimeException(
                'Unable to prepare case information for AI: ' .
                $e->getMessage()
            );
        }


        /*
        |--------------------------------------------------------------------------
        | System prompt
        |--------------------------------------------------------------------------
        */

        $systemPrompt = <<<'PROMPT'

You are an account recovery case analysis assistant.

Analyze the user's recovery case using BOTH:

1. Information explicitly provided by the user.
2. Information detected from uploaded screenshots by Vision AI.

You must distinguish between:

- user-provided information
- screenshot-derived information
- information that cannot be determined

Important rules:

1. Do not invent facts.
2. Do not assume a reason unless there is supporting evidence.
3. Treat screenshot analysis as evidence, not absolute truth.
4. If user information and screenshot evidence agree, confidence may increase.
5. If they conflict, mention the uncertainty in the summary.
6. Do not invent policy violations.
7. Do not claim certainty when evidence is incomplete.

Return ONLY valid JSON.

Use exactly this structure:

{
    "issue_detected": "string",
    "possible_reason": "string|null",
    "confidence": 0,
    "summary": "string",
    "recommended_action": "submit_appeal|secure_account|recover_account|provide_more_information"
}

Confidence must be an integer from 0 to 100.

PROMPT;


        /*
        |--------------------------------------------------------------------------
        | User prompt
        |--------------------------------------------------------------------------
        */

        $userPrompt = <<<PROMPT

Analyze this complete recovery case:

{$caseJson}

The screenshot_evidence section contains results produced
by a separate Vision AI analysis.

Use those results as supporting evidence.

Compare the user's description with the screenshot evidence.

For example:

User says:
"My account was disabled and I don't understand why."

Screenshot evidence says:
"Your account has been disabled."

These pieces of evidence support the same issue.

However, if the screenshot says something different from
the user's description, do not blindly choose one.
Mention the uncertainty in the summary.

Determine:

1. The most likely issue.
2. A possible reason only when supported by the evidence.
3. Confidence from 0 to 100.
4. A concise factual summary.
5. The most appropriate recovery action.

Return ONLY JSON.

PROMPT;


        /*
        |--------------------------------------------------------------------------
        | Call text AI
        |--------------------------------------------------------------------------
        */

        $response = $this->aiClient->generate(
            $systemPrompt,
            $userPrompt,
            true
        );


        /*
        |--------------------------------------------------------------------------
        | Parse JSON
        |--------------------------------------------------------------------------
        */

        $analysis = $this->parseResponse(
            $response
        );


        /*
        |--------------------------------------------------------------------------
        | Validate AI result
        |--------------------------------------------------------------------------
        */

        return $this->validateAnalysis(
            $analysis,
            $case
        );
    }


    /**
     * Parse the JSON returned by Ollama.
     */
    private function parseResponse(
        string $response
    ): array {

        $response = trim($response);


        /*
        | Remove accidental Markdown fences.
        */

        $response = preg_replace(
            '/^```(?:json)?\s*/i',
            '',
            $response
        );

        $response = preg_replace(
            '/\s*```$/',
            '',
            $response
        );


        $data = json_decode(
            trim($response),
            true
        );


        if (!is_array($data)) {

            throw new RuntimeException(
                'AI returned invalid analysis JSON: ' .
                $response
            );
        }


        return $data;
    }


    /**
     * Validate the final AI analysis.
     */
    private function validateAnalysis(
        array $data,
        RecoveryCase $case
    ): array {

        $validator = Validator::make(
            $data,
            [

                'issue_detected' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'possible_reason' => [
                    'nullable',
                    'string',
                    'max:500',
                ],

                'confidence' => [
                    'required',
                    'integer',
                    'min:0',
                    'max:100',
                ],

                'summary' => [
                    'required',
                    'string',
                    'max:2000',
                ],

                'recommended_action' => [
                    'required',
                    'string',
                    'in:submit_appeal,secure_account,recover_account,provide_more_information',
                ],

            ]
        );


        if ($validator->fails()) {

            throw new RuntimeException(
                'AI analysis failed validation: ' .
                $validator->errors()->toJson()
            );
        }


        $validated = $validator->validated();

/*
|--------------------------------------------------------------------------
| Enforce application recovery workflow
|--------------------------------------------------------------------------
|
| The AI analyzes the case, but Laravel determines the final
| recovery action based on the user's selected issue type.
|
*/
$validated['issue_detected'] = match ($case->issue_type) {

    'account_disabled' =>
        'Account disabled',

    'hacked_account' =>
        'Possible hacked account',

    'cant_login' =>
        "Can't log in",

    'other' =>
        'Other account issue',

    default =>
        'Unknown issue',
};

$validated['recommended_action'] = match ($case->issue_type) {

    'account_disabled' =>
        'submit_appeal',

    'hacked_account' =>
        'secure_account',

    'cant_login' =>
        'recover_account',

    'other' =>
        'provide_more_information',

    default =>
        'provide_more_information',
};

return $validated;;
    }
}