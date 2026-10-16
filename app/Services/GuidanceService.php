<?php

namespace App\Services;

use App\Models\RecoveryCase;

class GuidanceService
{
    public function getGuidance(RecoveryCase $case): array
    {
        $analysis = $case->analyses()
            ->latest()
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Guidance already generated
        |--------------------------------------------------------------------------
        |
        | This can be either:
        | - AI generated
        | - Rule-based fallback
        |
        */

        if ($analysis && is_array($analysis->guidance)) {
            return $this->formatGuidance(
                $analysis->guidance
            );
        }

        /*
        |--------------------------------------------------------------------------
        | No analysis yet
        |--------------------------------------------------------------------------
        */

        if (!$analysis) {
            return [
                'title' => 'More information is needed',

                'summary' =>
                    'Complete the case analysis before following a recovery path.',

                'recommended_action' =>
                    'provide_more_information',

                'steps' => [
                    'Complete the case analysis.',
                    'Review the information you provided.',
                    'Add relevant screenshots if available.',
                    'Continue once the analysis is complete.',
                ],

                'evidence' => [
                    'Account ownership proof',
                    'Relevant screenshots',
                    'Clear explanation of the issue',
                ],

                'avoid' => [
                    'Do not provide false information.',
                    'Do not share your password.',
                    'Do not share recovery codes.',
                ],

                'next_route' =>
                    'case.prepare',

                'next_button' =>
                    'Continue →',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Analysis exists but no guidance was generated
        |--------------------------------------------------------------------------
        |
        | Use the recommended action stored in the analysis.
        |
        */

        return match ($analysis->recommended_action) {

            /*
            |--------------------------------------------------------------------------
            | Account disabled
            |--------------------------------------------------------------------------
            */

            'submit_appeal' => [

                'title' =>
                    'Your account appears to be disabled',

                'summary' =>
                    'The recommended next step is to review the platform notification and prepare an appeal if you believe the action was incorrect.',

                'recommended_action' =>
                    'submit_appeal',

                'steps' => [

                    'Review the notification you received from the platform.',

                    'Check whether an official appeal option is available.',

                    'Prepare proof that you own the account.',

                    'Explain clearly what happened.',

                    'Submit the appeal through the platform\'s official recovery process.',
                ],

                'evidence' => [

                    'Account ownership proof',

                    'Screenshot of the disabled account message',

                    'Recent login information',

                    'Clear explanation of the situation',
                ],

                'avoid' => [

                    'Do not share your password.',

                    'Do not share recovery codes.',

                    'Do not provide false information.',

                    'Do not submit repeated appeals unnecessarily.',
                ],

                'next_route' =>
                    'case.prepare',

                'next_button' =>
                    'Prepare your appeal →',
            ],

            /*
            |--------------------------------------------------------------------------
            | Hacked account
            |--------------------------------------------------------------------------
            */

            'secure_account' => [

                'title' =>
                    'Your account may have been compromised',

                'summary' =>
                    'The priority is to secure the account and use the platform\'s official compromised-account recovery process.',

                'recommended_action' =>
                    'secure_account',

                'steps' => [

                    'Secure your email account first if necessary.',

                    'Change your password if you still have access.',

                    'Review recent account activity.',

                    'Remove unknown devices or sessions.',

                    'Use the platform\'s official hacked-account recovery process.',
                ],

                'evidence' => [

                    'Account ownership proof',

                    'Recent login details',

                    'Screenshot of suspicious activity',

                    'Details about when access was lost',
                ],

                'avoid' => [

                    'Do not share passwords.',

                    'Do not share authentication codes.',

                    'Do not trust unofficial recovery services.',
                ],

                'next_route' =>
                    'case.prepare',

                'next_button' =>
                    'Review security steps →',
            ],

            /*
            |--------------------------------------------------------------------------
            | Can't login
            |--------------------------------------------------------------------------
            */

            'recover_account' => [

                'title' =>
                    'You are having trouble accessing your account',

                'summary' =>
                    'The recommended approach is to verify your login information and use the platform\'s official account recovery options.',

                'recommended_action' =>
                    'recover_account',

                'steps' => [

                    'Verify your username or email address.',

                    'Check whether your password is correct.',

                    'Try the official password recovery option.',

                    'Check your email for recovery instructions.',

                    'Follow the platform\'s official account recovery process.',
                ],

                'evidence' => [

                    'Account ownership proof',

                    'Recovery email information',

                    'Recent login information',
                ],

                'avoid' => [

                    'Do not share your password.',

                    'Do not share recovery codes.',

                    'Do not use unofficial recovery websites.',
                ],

                'next_route' =>
                    'case.prepare',

                'next_button' =>
                    'Review recovery steps →',
            ],

            /*
            |--------------------------------------------------------------------------
            | More information
            |--------------------------------------------------------------------------
            */

            default => [

                'title' =>
                    'More information is needed',

                'summary' =>
                    'The available information is not enough to determine the best recovery path.',

                'recommended_action' =>
                    'provide_more_information',

                'steps' => [

                    'Review the information you entered.',

                    'Add a clear explanation of the problem.',

                    'Provide relevant screenshots.',

                    'Add any useful account information.',
                ],

                'evidence' => [

                    'Account ownership proof',

                    'Relevant screenshots',

                    'Clear explanation',
                ],

                'avoid' => [

                    'Do not provide false information.',

                    'Do not share passwords or recovery codes.',
                ],

                'next_route' =>
                    'case.prepare',

                'next_button' =>
                    'Continue →',
            ],
        };
    }

    /*
    |--------------------------------------------------------------------------
    | Format generated guidance
    |--------------------------------------------------------------------------
    |
    | Works for BOTH:
    |
    | AI generated guidance
    | Rule-based fallback guidance
    |
    */

    protected function formatGuidance(array $guidance): array
    {
        $action = $guidance['recommended_action']
            ?? 'provide_more_information';

        $titles = [
            'submit_appeal' =>
                'Prepare and submit your appeal',

            'secure_account' =>
                'Secure your account',

            'recover_account' =>
                'Recover your account',

            'provide_more_information' =>
                'Provide more information',
        ];

        $buttons = [
            'submit_appeal' =>
                'Prepare your appeal →',

            'secure_account' =>
                'Review security steps →',

            'recover_account' =>
                'Review recovery steps →',

            'provide_more_information' =>
                'Continue →',
        ];

        return [
            'title' =>
                $titles[$action]
                ?? $titles['provide_more_information'],

            'summary' =>
                'Personalized guidance was generated from your case analysis.',

            'recommended_action' =>
                $action,

            'steps' =>
                $guidance['steps'] ?? [],

            'evidence' =>
                $guidance['evidence'] ?? [],

            /*
            |--------------------------------------------------------------------------
            | GuidanceAIService stores warnings
            | but the Blade/UI expects "avoid"
            |--------------------------------------------------------------------------
            */

            'avoid' =>
                $guidance['warnings']
                ?? $guidance['avoid']
                ?? [],

            'next_route' =>
                'case.prepare',

            'next_button' =>
                $buttons[$action]
                ?? $buttons['provide_more_information'],
        ];
    }
}