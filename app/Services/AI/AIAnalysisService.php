<?php

namespace App\Services\AI;

use App\Models\RecoveryCase;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class AIAnalysisService
{
    public function __construct(
        protected AIClient $client
    ) {
    }

    /**
     * Analyze the recovery case.
     *
     * Ollama available:
     *      AI-generated analysis
     *
     * Ollama unavailable:
     *      Local rule-based analysis
     */
    public function analyze(RecoveryCase $case): array
    {
        /*
        |--------------------------------------------------------------------------
        | AI prompt
        |--------------------------------------------------------------------------
        */

        $systemPrompt = <<<'PROMPT'
You are an account recovery analysis assistant.

Analyze the user's account recovery case.

Return ONLY valid JSON.

Do not return Markdown.
Do not return explanations outside JSON.

The JSON must have exactly these fields:

{
    "issue_type": "account_disabled|hacked_account|cant_login|other",
    "confidence": 0,
    "possible_reason": "",
    "summary": "",
    "recommended_action": "submit_appeal|secure_account|recover_account|provide_more_information"
}

Rules:

- confidence must be a number from 0 to 100.
- Do not invent facts.
- Use only the information provided.
- If the account appears compromised, use "secure_account".
- If the account is disabled or restricted, use "submit_appeal".
- If the user cannot log in, use "recover_account".
PROMPT;

        /*
        |--------------------------------------------------------------------------
        | User information
        |--------------------------------------------------------------------------
        */

        $userPrompt = json_encode([
            'issue_type' => $case->issue_type,
            'description' => $case->description,
            'username' => $case->username,
            'email' => $case->email,
            'account_type' => $case->account_type,
        ], JSON_PRETTY_PRINT);

        /*
        |--------------------------------------------------------------------------
        | Try AI
        |--------------------------------------------------------------------------
        */

        try {

            Log::info(
                'Attempting AI case analysis.',
                [
                    'case_id' => $case->id,
                ]
            );

            $content = $this->client->generate(
                $systemPrompt,
                $userPrompt,
                true
            );

            /*
            |--------------------------------------------------------------------------
            | Decode AI response
            |--------------------------------------------------------------------------
            */

            $data = json_decode(
                $content,
                true
            );

            if (
                json_last_error() !== JSON_ERROR_NONE ||
                !is_array($data)
            ) {
                throw new RuntimeException(
                    'AI returned invalid analysis JSON.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Validate AI response
            |--------------------------------------------------------------------------
            */

            $result = $this->validate($data);

            Log::info(
                'AI case analysis generated successfully.',
                [
                    'case_id' => $case->id,
                    'source' => 'ai',
                ]
            );

            return $result;

        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | IMPORTANT
            |--------------------------------------------------------------------------
            |
            | Ollama is unavailable.
            |
            | DO NOT call another AI service here.
            |
            | Use completely local rule-based analysis.
            |
            */

            Log::warning(
                'AI case analysis failed. Using local rule-based fallback.',
                [
                    'case_id' => $case->id,
                    'error' => $e->getMessage(),
                ]
            );

            return $this->ruleBasedAnalysis($case);
        }
    }

    /**
     * Local rule-based analysis.
     *
     * IMPORTANT:
     *
     * This method does NOT call Ollama.
     * This method does NOT use AIClient.
     */
    protected function ruleBasedAnalysis(
        RecoveryCase $case
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Account disabled
        |--------------------------------------------------------------------------
        */

        if ($case->issue_type === 'account_disabled') {

            return [

                'issue_type' => 'account_disabled',

                'confidence' => 70,

                'possible_reason' =>
                    'The account may have been restricted or disabled because of a platform policy or community-guideline issue. The exact reason cannot be confirmed without an official platform response.',

                'summary' =>
                    'Your account appears to be disabled or restricted. A formal appeal or review request is the recommended next step.',

                'recommended_action' =>
                    'submit_appeal',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Hacked account
        |--------------------------------------------------------------------------
        */

        if ($case->issue_type === 'hacked_account') {

            return [

                'issue_type' => 'hacked_account',

                'confidence' => 70,

                'possible_reason' =>
                    'The account may have been compromised or accessed by someone else. The exact cause cannot be confirmed from the provided information alone.',

                'summary' =>
                    'Your account may have been compromised. Securing the account and reviewing account recovery options is recommended.',

                'recommended_action' =>
                    'secure_account',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Cannot log in
        |--------------------------------------------------------------------------
        */

        if ($case->issue_type === 'cant_login') {

            return [

                'issue_type' => 'cant_login',

                'confidence' => 65,

                'possible_reason' =>
                    'The login problem may be related to incorrect credentials, account recovery requirements, security checks, or another login-related issue.',

                'summary' =>
                    'You are unable to access your account. The recommended next step is to review the available account recovery process.',

                'recommended_action' =>
                    'recover_account',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Other
        |--------------------------------------------------------------------------
        */

        return [

            'issue_type' => 'other',

            'confidence' => 40,

            'possible_reason' =>
                'There is not enough information to determine the exact cause of the account issue.',

            'summary' =>
                'The available information is not sufficient to determine the exact account problem. Additional information may be required.',

            'recommended_action' =>
                'provide_more_information',
        ];
    }

    /**
     * Validate AI response.
     */
    protected function validate(array $data): array
    {
        $allowedIssueTypes = [
            'account_disabled',
            'hacked_account',
            'cant_login',
            'other',
        ];

        $allowedActions = [
            'submit_appeal',
            'secure_account',
            'recover_account',
            'provide_more_information',
        ];

        $issueType = $data['issue_type']
            ?? 'other';

        if (!in_array(
            $issueType,
            $allowedIssueTypes,
            true
        )) {
            $issueType = 'other';
        }

        $action = $data['recommended_action']
            ?? 'provide_more_information';

        if (!in_array(
            $action,
            $allowedActions,
            true
        )) {
            $action = 'provide_more_information';
        }

        $confidence = (float) (
            $data['confidence'] ?? 0
        );

        $confidence = max(
            0,
            min(100, $confidence)
        );

        return [

            'issue_type' =>
                $issueType,

            'confidence' =>
                $confidence,

            'possible_reason' =>
                isset($data['possible_reason'])
                    ? trim(
                        (string) $data['possible_reason']
                    )
                    : null,

            'summary' =>
                isset($data['summary'])
                    ? trim(
                        (string) $data['summary']
                    )
                    : 'Unable to determine the issue.',

            'recommended_action' =>
                $action,
        ];
    }
}