<?php

namespace App\Services;

use App\Models\RecoveryCase;

class RecoveryRecommendationService
{
    public function recommend(RecoveryCase $case): array
    {
        $analysis = $case->analyses()
            ->latest()
            ->first();

        if (!$analysis) {
            return [
                'title' => 'Provide more information',
                'description' =>
                    'Additional information is needed before a recovery path can be recommended.',
                'action' => 'provide_more_information',
                'button' => 'Provide more information →',
            ];
        }

        return match ($analysis->recommended_action) {

            'submit_appeal' => [
                'title' => 'Submit your appeal',
                'description' =>
                    'Review the generated appeal and submit it through the platform\'s official recovery or appeal process.',
                'action' => 'submit_appeal',
                'button' => 'Review & submit appeal →',
            ],

            'secure_account' => [
                'title' => 'Secure your account',
                'description' =>
                    'Use the platform\'s official compromised-account recovery process and follow its security instructions.',
                'action' => 'secure_account',
                'button' => 'Review security steps →',
            ],

            'recover_account' => [
                'title' => 'Recover your account',
                'description' =>
                    'Use the platform\'s official login and account recovery options.',
                'action' => 'recover_account',
                'button' => 'Review recovery steps →',
            ],

            default => [
                'title' => 'Provide more information',
                'description' =>
                    'Additional context may help determine the appropriate recovery path.',
                'action' => 'provide_more_information',
                'button' => 'Provide more information →',
            ],
        };
    }
}