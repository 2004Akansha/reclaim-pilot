<?php

namespace App\Services;

use App\Models\RecoveryCase;
use App\Services\AI\AIClient;
use RuntimeException;

class AppealGeneratorService
{
    /**
     * AI client used to communicate with Ollama.
     */
    protected AIClient $aiClient;

    /**
     * Inject AIClient through Laravel's service container.
     */
    public function __construct(AIClient $aiClient)
    {
        $this->aiClient = $aiClient;
    }

    /**
     * Generate an AI-powered recovery appeal.
     */
    public function generate(RecoveryCase $case): string
    {
        /*
         * ---------------------------------------------------------
         * 1. Get the latest analysis for this case
         * ---------------------------------------------------------
         */

        $analysis = $case->analyses()
            ->latest()
            ->first();

        /*
         * An appeal should only be generated after
         * the case has been analyzed.
         */
        if (!$analysis) {
            throw new RuntimeException(
                'Case must be analyzed before generating an appeal.'
            );
        }

        /*
         * ---------------------------------------------------------
         * 2. Prepare safe fallback values
         * ---------------------------------------------------------
         *
         * IMPORTANT:
         *
         * Do NOT write things like:
         *
         * {$case->username ?: 'Not provided'}
         *
         * directly inside a heredoc.
         *
         * PHP can produce:
         *
         * unexpected token "?"
         *
         * So we calculate the values first.
         */

        $issueDetected = $analysis->issue_detected
            ?: 'Account issue';

        $possibleReason = $analysis->possible_reason
            ?: 'Not determined';

        $summary = $analysis->summary
            ?: 'No analysis summary was available.';

        $description = $case->description
            ?: 'No additional explanation was provided.';

        $username = $case->username
            ?: 'Not provided';

        $email = $case->email
            ?: 'Not provided';

        /*
         * ---------------------------------------------------------
         * 3. System prompt
         * ---------------------------------------------------------
         */

        $systemPrompt = <<<'PROMPT'
You are an assistant that helps users draft
clear and respectful account-recovery appeals.

Generate a concise and professional appeal based
only on the information provided by the user.

Important rules:

1. Do not invent facts.
2. Do not invent policy violations.
3. Do not falsely claim that the user did or did not
   violate a policy unless the user explicitly provided
   that information.
4. Do not guarantee account recovery.
5. Do not threaten the platform.
6. Do not use aggressive language.
7. Do not include passwords, authentication codes,
   or other sensitive security credentials.
8. Clearly request that the account be reviewed.
9. Keep the appeal professional and respectful.
10. Return ONLY the appeal text.
11. Do not return JSON.
12. Do not return Markdown code fences.

The appeal should be suitable for submission
through an official account recovery or appeal process.
PROMPT;

        /*
         * ---------------------------------------------------------
         * 4. User prompt
         * ---------------------------------------------------------
         */

        $userPrompt = <<<PROMPT
Create an account recovery appeal using the information below.

Identified issue:
{$issueDetected}

Possible reason:
{$possibleReason}

Case summary:
{$summary}

User's description:
{$description}

Username:
{$username}

Email:
{$email}

Write a professional and respectful appeal requesting
that the support team review the account and the action
taken against it.

Do not invent information that is not present above.

Return only the final appeal text.
PROMPT;

        /*
         * ---------------------------------------------------------
         * 5. Send request to Ollama
         * ---------------------------------------------------------
         */

        $appeal = $this->aiClient->generate(
            $systemPrompt,
            $userPrompt
        );

        /*
         * ---------------------------------------------------------
         * 6. Final safety check
         * ---------------------------------------------------------
         */

        $appeal = trim($appeal);

        if ($appeal === '') {
            throw new RuntimeException(
                'AI returned an empty appeal.'
            );
        }

        return $appeal;
    }
}