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
        | Load additional case information
        |--------------------------------------------------------------------------
        |
        | followups:
        |     Additional information provided by the user after
        |     the original case submission.
        |
        | screenshots.analysis:
        |     Any analysis already performed on uploaded screenshots.
        |
        */

        $case->loadMissing([
            'followups',
            'screenshots.analysis',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Build follow-up information
        |--------------------------------------------------------------------------
        */

        $followupInformation = $case->followups
            ->map(function ($followup) {
                return [
                    'message' => $followup->message,
                    'created_at' => $followup->created_at?->toDateTimeString(),
                ];
            })
            ->values()
            ->all();

        /*
        |--------------------------------------------------------------------------
        | Build screenshot information
        |--------------------------------------------------------------------------
        */

        $screenshotInformation = $case->screenshots
            ->map(function ($screenshot) {
                $analysis = $screenshot->analysis;

                return [
                    'filename' => $screenshot->original_name,
                    'detected_issue' => $analysis?->detected_issue,
                    'visible_message' => $analysis?->visible_message,
                    'possible_reason' => $analysis?->possible_reason,
                    'confidence' => $analysis?->confidence,
                ];
            })
            ->values()
            ->all();

        /*
        |--------------------------------------------------------------------------
        | AI system prompt
        |--------------------------------------------------------------------------
        */

        $systemPrompt = <<<'PROMPT'
You are an account recovery analysis assistant.

Analyze the user's account recovery case.

The case may contain:
1. Original case information
2. Uploaded screenshot evidence
3. Follow-up information added after the original submission

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
- If there is not enough information, use "provide_more_information".

Follow-up information:

The case may contain follow-up information added after the original submission.

Follow-up information is additional user-provided evidence or context.

You MUST consider both:

1. The original case information.
2. All follow-up information.

If a follow-up changes the interpretation of the case, reflect that change in the analysis.

Do not ignore follow-up information.

Do not assume that a follow-up is automatically true just because the user provided it.

Treat follow-up information as user-provided evidence.

If the information is uncertain or conflicts with other information, describe the uncertainty instead of inventing a fact.

Screenshot information:

Uploaded screenshots may contain additional evidence.

Consider screenshot information together with the original case and follow-ups.

Do not assume that information not visible in a screenshot is present.

The final analysis should reflect the strongest evidence available while remaining appropriately uncertain.
PROMPT;

        /*
        |--------------------------------------------------------------------------
        | User information
        |--------------------------------------------------------------------------
        */

        $userPrompt = json_encode([
            'original_case' => [
                'issue_type' => $case->issue_type,
                'description' => $case->description,
                'username' => $case->username,
                'email' => $case->email,
                'account_type' => $case->account_type,
            ],

            'followups' => $followupInformation,

            'screenshots' => $screenshotInformation,

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
                    'followup_count' => count($followupInformation),
                    'screenshot_count' => count($screenshotInformation),
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
                    'followup_count' => count($followupInformation),
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
        $case->loadMissing('followups');

        $followupText = $case->followups
            ->pluck('message')
            ->implode(' ');

        $combinedText = strtolower(
            trim(
                ($case->description ?? '') . ' ' . $followupText
            )
        );

        /*
        |--------------------------------------------------------------------------
        | Account disabled
        |--------------------------------------------------------------------------
        */

        if ($case->issue_type === 'account_disabled') {

            $possibleReason =
                'The account may have been restricted or disabled because of a platform policy or community-guideline issue. The exact reason cannot be confirmed without an official platform response.';

            $summary =
                'Your account appears to be disabled or restricted. A formal appeal or review request is the recommended next step.';

            if (
                str_contains($combinedText, 'community guideline') ||
                str_contains($combinedText, 'community guidelines') ||
                str_contains($combinedText, 'policy') ||
                str_contains($combinedText, 'violation')
            ) {
                $possibleReason =
                    'The provided information mentions a possible policy or community-guideline issue. The exact platform decision should be confirmed from the official notification.';

                $summary =
                    'Your account appears to be disabled or restricted, and the additional information mentions a possible policy or community-guideline notification. The recommended next step is to review that notification and prepare an appropriate appeal.';
            }

            return [
                'issue_type' => 'account_disabled',

                'confidence' => 75,

                'possible_reason' => $possibleReason,

                'summary' => $summary,

                'recommended_action' => 'submit_appeal',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Hacked account
        |--------------------------------------------------------------------------
        */

        if ($case->issue_type === 'hacked_account') {

            $possibleReason =
                'The account may have been compromised or accessed by someone else. The exact cause cannot be confirmed from the provided information alone.';

            $summary =
                'Your account may have been compromised. Securing the account and reviewing account recovery options is recommended.';

            if (
                str_contains($combinedText, 'email changed') ||
                str_contains($combinedText, 'email was changed') ||
                str_contains($combinedText, 'changed my email')
            ) {
                $possibleReason =
                    'The additional information indicates that the account email may have been changed unexpectedly, which can be a sign of possible account compromise.';

                $summary =
                    'The account may have been compromised, and the additional information indicates an unexpected email change. The official compromised-account recovery process should be used.';
            }

            if (
                str_contains($combinedText, 'logged out') ||
                str_contains($combinedText, 'lost access')
            ) {
                $summary .=
                    ' The additional information also indicates a possible loss of account access.';
            }

            return [
                'issue_type' => 'hacked_account',

                'confidence' => 75,

                'possible_reason' => $possibleReason,

                'summary' => $summary,

                'recommended_action' => 'secure_account',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Cannot log in
        |--------------------------------------------------------------------------
        */

        if ($case->issue_type === 'cant_login') {

            $possibleReason =
                'The login problem may be related to incorrect credentials, account recovery requirements, security checks, or another login-related issue.';

            $summary =
                'You are unable to access your account. The recommended next step is to review the available account recovery process.';

            if (
                str_contains($combinedText, 'password')
            ) {
                $possibleReason =
                    'The additional information indicates that the login problem may be related to the account password or password recovery process.';

                $summary =
                    'You are unable to access your account and the additional information mentions a password-related problem. The official password or account recovery process should be reviewed.';
            }

            if (
                str_contains($combinedText, 'verification') ||
                str_contains($combinedText, 'verification code')
            ) {
                $summary .=
                    ' The additional information also mentions a verification-related problem.';
            }

            return [
                'issue_type' => 'cant_login',

                'confidence' => 70,

                'possible_reason' => $possibleReason,

                'summary' => $summary,

                'recommended_action' => 'recover_account',
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