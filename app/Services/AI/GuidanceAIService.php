<?php

namespace App\Services\AI;

use App\Models\RecoveryCase;
use App\Models\Analysis;
use RuntimeException;
use Illuminate\Support\Facades\Log;

class GuidanceAIService
{
    public function __construct(
        protected AIClient $client
    ) {
    }

    /**
     * Generate case-specific recovery guidance.
     *
     * AI available:
     *      Generates guidance using the complete case,
     *      analysis and screenshot evidence.
     *
     * AI unavailable:
     *      Uses local case-specific rule-based guidance.
     *
     * IMPORTANT:
     *      The recommended_action from the Analysis model is
     *      authoritative. AI is NOT allowed to change it.
     */
    public function generate(
        RecoveryCase $case,
        Analysis $analysis
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Load screenshot evidence
        |--------------------------------------------------------------------------
        */

        $case->loadMissing([
            'followups',
            'screenshots.analysis',
        ]);

        $followupInformation = $case->followups
            ->map(function ($followup) {
                return [
                    'message' => $followup->message,
                    'created_at' => $followup->created_at?->toDateTimeString(),
                ];
            })
            ->values()
            ->all();

        $screenshotEvidence = $case->screenshots
            ->map(function ($screenshot) {

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
        | Determine authoritative workflow action
        |--------------------------------------------------------------------------
        |
        | The Analysis result controls the workflow.
        |
        | AI can customize the guidance, but it cannot change the action.
        |
        */

        $authoritativeAction =
            $this->getAuthoritativeAction($case, $analysis);

        /*
        |--------------------------------------------------------------------------
        | System prompt
        |--------------------------------------------------------------------------
        */

        $systemPrompt = <<<'PROMPT'
You are an account recovery guidance assistant.

Your job is to create SAFE, PRACTICAL and CASE-SPECIFIC recovery guidance.

The guidance must be based on:

1. The user's original issue type.
2. The user's original description.
3. The account type.
4. Follow-up information provided after the original case.
5. The final case analysis.
6. The possible reason.
7. The analysis confidence.
8. Screenshot evidence.
9. The authoritative recovery action.

The guidance must NOT be generic when useful case-specific information
is available.

FOLLOW-UP INFORMATION RULES:

Follow-up information represents additional information provided by the
user after the original case was created.

Treat follow-up information as additional evidence.

Use it when it provides useful details about:
- what happened
- when the problem occurred
- messages received by the user
- unexpected account changes
- login problems
- policy or restriction notifications
- recovery attempts

Do not ignore the original case description.

Combine the original case information with the follow-up information.

If follow-up information conflicts with the original description,
acknowledge the conflict rather than inventing an explanation.

Do not treat a user's statement as independently verified evidence.

IMPORTANT WORKFLOW RULE:

The recommended_action supplied by the application is AUTHORITATIVE.

You MUST return the exact same recommended_action.

Do NOT change the recommended_action.

Use this workflow mapping:

account_disabled → submit_appeal
hacked_account → secure_account
cant_login → recover_account
other → provide_more_information

For example:

If the case is hacked_account and the authoritative action is
secure_account, you MUST return:

"recommended_action": "secure_account"

Do not change it to "recover_account" simply because the user
eventually needs to regain access.

The AI is responsible for generating useful guidance.

Laravel is responsible for controlling the workflow action.

IMPORTANT SAFETY RULES:

- Give practical and safe instructions.
- Make the guidance specific to the case.
- Use the user's actual description.
- Use screenshot evidence when relevant.
- If the screenshot contains a visible message, mention it when useful.
- Do not invent evidence.
- Clearly distinguish existing evidence from evidence the user may need.
- Do not claim that an account was definitely hacked unless the evidence
  establishes that.
- Do not claim that an account will definitely be restored.
- Never ask for passwords.
- Never ask for authentication codes.
- Never ask for recovery codes.
- Never recommend unofficial recovery services.
- Never recommend suspicious links or third-party account recovery services.
- Never invent platform-specific policies.
- Do not tell the user to use an old password if the account may be compromised.
- Do not tell the user to restore an email address unless the official
  platform recovery process provides such an option.
- Do not instruct the user to access another person's account.

HACKED ACCOUNT RULES:

For hacked-account cases:

- Treat unauthorized email changes as important evidence of possible
  account compromise.
- Treat unexpected logout as important loss-of-access information.
- If screenshot evidence contains a message such as "HACKED!", use it
  as supporting evidence.
- Recommend the platform's official compromised-account recovery process.
- Tell the user to report unexpected email or phone changes when relevant.
- Tell the user to include when they lost access when that information
  is available.
- Do not assume the attacker still has access.
- Do not assume the user can still use their original password.
- Do not instruct the user to bypass platform security.

RETURN FORMAT:

Return ONLY valid JSON.

Do not return Markdown.

Do not return explanations outside JSON.

Return exactly this structure:

{
    "recommended_action": "secure_account",
    "steps": [
        "step 1",
        "step 2",
        "step 3"
    ],
    "evidence": [
        "evidence item 1",
        "evidence item 2"
    ],
    "warnings": [
        "warning 1",
        "warning 2"
    ]
}

CONTENT RULES:

- Use 3 to 6 practical steps.
- Use 2 to 6 evidence items.
- Use 2 to 5 warnings.
- Steps should be specific to the user's case.
- Evidence should distinguish between existing evidence and useful evidence
  the user may need.
- Warnings should be relevant to the specific case.
PROMPT;

        /*
        |--------------------------------------------------------------------------
        | Prepare case information
        |--------------------------------------------------------------------------
        */

        $userPrompt = json_encode([
            'case' => [
                'issue_type' =>
                    $case->issue_type,

                'description' =>
                    $case->description,

                'account_type' =>
                    $case->account_type,

                'username_provided' =>
                    !empty($case->username),

                'email_provided' =>
                    !empty($case->email),
            ],

            /*
            |--------------------------------------------------------------------------
            | Follow-up information
            |--------------------------------------------------------------------------
            */

            'followup_information' =>
                $followupInformation,


            'analysis' => [
                'issue_detected' =>
                    $analysis->issue_detected,

                'possible_reason' =>
                    $analysis->possible_reason,

                'confidence' =>
                    $analysis->confidence,

                'summary' =>
                    $analysis->summary,

                'recommended_action' =>
                    $analysis->recommended_action,
            ],

            /*
            |--------------------------------------------------------------------------
            | Explicit workflow instruction
            |--------------------------------------------------------------------------
            */

            'workflow' => [
                'authoritative_action' =>
                    $authoritativeAction,
            ],

            /*
            |--------------------------------------------------------------------------
            | Screenshot evidence
            |--------------------------------------------------------------------------
            */

            'screenshot_evidence' =>
                $screenshotEvidence,

        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        /*
        |--------------------------------------------------------------------------
        | Try AI
        |--------------------------------------------------------------------------
        */

        try {

            Log::info(
                'Attempting case-specific AI recovery guidance.',
                [
                    'case_id' =>
                        $case->id,

                    'analysis_id' =>
                        $analysis->id,

                    'authoritative_action' =>
                        $authoritativeAction,
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
                    'AI returned invalid guidance JSON: ' .
                    json_last_error_msg()
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Validate AI response
            |--------------------------------------------------------------------------
            |
            | IMPORTANT:
            |
            | The authoritative action is passed into validate().
            |
            | Therefore the AI cannot change the workflow action.
            |
            */

            $guidance = $this->validate(
                $data,
                $authoritativeAction
            );

            Log::info(
                'Case-specific AI guidance generated successfully.',
                [
                    'case_id' =>
                        $case->id,

                    'analysis_id' =>
                        $analysis->id,

                    'source' =>
                        'ai',

                    'recommended_action' =>
                        $guidance['recommended_action'],
                ]
            );

            return [
                'guidance' =>
                    $guidance,

                'source' =>
                    'ai',
            ];

        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | AI unavailable / invalid
            |--------------------------------------------------------------------------
            |
            | IMPORTANT:
            |
            | Do not call another AI service here.
            |
            | Use local case-specific rules.
            |
            */

            Log::warning(
                'AI guidance failed. Using case-specific rule-based guidance.',
                [
                    'case_id' =>
                        $case->id,

                    'analysis_id' =>
                        $analysis->id,

                    'authoritative_action' =>
                        $authoritativeAction,

                    'error' =>
                        $e->getMessage(),
                ]
            );

            $guidance = $this->ruleBasedGuidance(
                $case,
                $analysis,
                $authoritativeAction
            );

            return [
                'guidance' =>
                    $guidance,

                'source' =>
                    'rule_based',
            ];
        }
    }

    /**
     * Determine the authoritative workflow action.
     *
     * The Analysis model controls this.
     */
    protected function getAuthoritativeAction(
        RecoveryCase $case,
        Analysis $analysis
    ): string {

        $allowedActions = [
            'submit_appeal',
            'secure_account',
            'recover_account',
            'provide_more_information',
        ];

        /*
        |--------------------------------------------------------------------------
        | First choice:
        | Use the action already produced by the analysis.
        |--------------------------------------------------------------------------
        */

        if (
            !empty($analysis->recommended_action) &&
            in_array(
                $analysis->recommended_action,
                $allowedActions,
                true
            )
        ) {
            return $analysis->recommended_action;
        }

        /*
        |--------------------------------------------------------------------------
        | Safe fallback based on case type
        |--------------------------------------------------------------------------
        */

        return match ($case->issue_type) {

            'account_disabled' =>
                'submit_appeal',

            'hacked_account' =>
                'secure_account',

            'cant_login' =>
                'recover_account',

            default =>
                'provide_more_information',
        };
    }

    /**
     * Generate case-specific rule-based guidance.
     */
    protected function ruleBasedGuidance(
        RecoveryCase $case,
        Analysis $analysis,
        string $authoritativeAction
    ): array {

        $case->loadMissing('followups');

        $followupText = $case->followups
            ->pluck('message')
            ->implode(' ');

        $description = strtolower(
            trim(
                ($case->description ?? '') . ' ' . $followupText
            )
        );

        /*
        |--------------------------------------------------------------------------
        | Account disabled
        |--------------------------------------------------------------------------
        */

        if (
            $authoritativeAction ===
            'submit_appeal'
        ) {

            $steps = [
                'Review the exact notification or message shown when the account was disabled.',

                'Confirm that the account belongs to you using appropriate ownership information.',
            ];

            /*
            |--------------------------------------------------------------------------
            | Description-specific rules
            |--------------------------------------------------------------------------
            */

            if (
                str_contains($description, 'mistake') ||
                str_contains($description, 'wrong') ||
                str_contains($description, 'error')
            ) {

                $steps[] =
                    'Explain clearly in your appeal why you believe the restriction was made in error.';

            } elseif (
                str_contains($description, 'policy') ||
                str_contains($description, 'guideline') ||
                str_contains($description, 'violation')
            ) {

                $steps[] =
                    'Address the policy or guideline concern mentioned in the account notification without making unsupported claims.';

            } else {

                $steps[] =
                    'Describe when the account became unavailable and what happened immediately before the restriction.';
            }

            $steps[] =
                'Attach relevant evidence such as the account notification or ownership information.';

            $steps[] =
                'Submit the appeal through the platform\'s official recovery process.';

            return [

                'recommended_action' =>
                    'submit_appeal',

                'steps' =>
                    array_slice(
                        $steps,
                        0,
                        6
                    ),

                'evidence' => [

                    'Account ownership information.',

                    'Screenshot of the disabled account message.',

                    'Relevant notification or warning.',

                    'Clear explanation of what happened.',
                ],

                'warnings' => [

                    'Do not share your password.',

                    'Do not share recovery codes.',

                    'Do not provide false information.',

                    'Do not claim a specific policy violation unless the platform notification supports it.',
                ],
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Hacked account
        |--------------------------------------------------------------------------
        */

        if (
            $authoritativeAction ===
            'secure_account'
        ) {

            $steps = [

                'Use the platform\'s official compromised-account recovery process rather than an unofficial recovery service.',

            ];

            /*
            |--------------------------------------------------------------------------
            | Email changed
            |--------------------------------------------------------------------------
            */

            if (
                str_contains($description, 'email') ||
                str_contains($description, 'changed')
            ) {

                $steps[] =
                    'Include the unexpected email change in your recovery request and provide the original account email if the official process asks for it.';
            }

            /*
            |--------------------------------------------------------------------------
            | Logged out / access lost
            |--------------------------------------------------------------------------
            */

            if (
                str_contains($description, 'login') ||
                str_contains($description, 'logged out') ||
                str_contains($description, 'access')
            ) {

                $steps[] =
                    'Explain when you lost access and that the unexpected logout occurred after the account changes.';
            }

            /*
            |--------------------------------------------------------------------------
            | Screenshot evidence
            |--------------------------------------------------------------------------
            */

            $hasHackEvidence =
                $case->screenshots
                    ->contains(function ($screenshot) {

                        if (!$screenshot->analysis) {
                            return false;
                        }

                        $message = strtolower(
                            (string) $screenshot
                                ->analysis
                                ->visible_message
                        );

                        $issue = strtolower(
                            (string) $screenshot
                                ->analysis
                                ->detected_issue
                        );

                        return
                            str_contains(
                                $message,
                                'hacked'
                            )
                            ||
                            str_contains(
                                $issue,
                                'hacked'
                            );
                    });

            if ($hasHackEvidence) {

                $steps[] =
                    'Include the uploaded screenshot showing the visible hacked-account message as supporting evidence.';
            }

            /*
            |--------------------------------------------------------------------------
            | Security review
            |--------------------------------------------------------------------------
            */

            $steps[] =
                'Review recent account activity and remove unknown sessions if the platform provides that option after access is restored.';

            /*
            |--------------------------------------------------------------------------
            | Limit to maximum six steps
            |--------------------------------------------------------------------------
            */

            $steps = array_slice(
                $steps,
                0,
                6
            );

            return [

                'recommended_action' =>
                    'secure_account',

                'steps' =>
                    $steps,

                'evidence' => [

                    'Account ownership information.',

                    'Details about the unexpected email change.',

                    'Details about when access was lost.',

                    'Uploaded screenshot showing the account problem.',
                ],

                'warnings' => [

                    'Do not share your password.',

                    'Do not share authentication or recovery codes.',

                    'Do not use unofficial account recovery services.',

                    'Do not attempt to access another person\'s account.',
                ],
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Cannot login
        |--------------------------------------------------------------------------
        */

        if (
            $authoritativeAction ===
            'recover_account'
        ) {

            $steps = [

                'Verify that the username or email address entered for recovery is correct.',

                'Use the platform\'s official password or account recovery option.',
            ];

            if (
                str_contains(
                    $description,
                    'password'
                )
            ) {

                $steps[] =
                    'Use the official password reset process instead of repeatedly attempting passwords.';

            } elseif (
                str_contains(
                    $description,
                    'email'
                )
            ) {

                $steps[] =
                    'Check the email account associated with the profile for official recovery instructions.';

            } elseif (
                str_contains(
                    $description,
                    'code'
                )
                ||
                str_contains(
                    $description,
                    'verification'
                )
            ) {

                $steps[] =
                    'Follow the platform\'s official verification or recovery process and use only legitimate verification methods.';

            } else {

                $steps[] =
                    'Record the exact login error or message so it can be included in the recovery request.';
            }

            $steps[] =
                'Provide account ownership information if the official recovery process requests it.';

            $steps[] =
                'Follow the platform\'s official account recovery process to completion.';

            return [

                'recommended_action' =>
                    'recover_account',

                'steps' =>
                    array_slice(
                        $steps,
                        0,
                        6
                    ),

                'evidence' => [

                    'Account ownership information.',

                    'Screenshot of the login error.',

                    'Recovery email information.',

                    'Recent login information.',
                ],

                'warnings' => [

                    'Do not share your password.',

                    'Do not share recovery codes.',

                    'Do not use unofficial recovery websites.',

                    'Do not repeatedly submit inaccurate recovery information.',
                ],
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Other / insufficient information
        |--------------------------------------------------------------------------
        */

        $steps = [

            'Review the information already entered for the recovery case.',
        ];

        if (
            !empty($case->description)
        ) {

            $steps[] =
                'Make the problem description as specific as possible, including what happened and when access was affected.';

        } else {

            $steps[] =
                'Add a clear description of what happened and when the account problem started.';
        }

        if (
            $case->screenshots->count() > 0
        ) {

            $steps[] =
                'Review the uploaded screenshots and make sure they clearly show the relevant account problem.';

        } else {

            $steps[] =
                'Add a relevant screenshot of the account problem if one is available.';
        }

        $steps[] =
            'Provide appropriate account ownership information.';

        $steps[] =
            'Continue through the official recovery process once enough information is available.';

        return [

            'recommended_action' =>
                'provide_more_information',

            'steps' =>
                array_slice(
                    $steps,
                    0,
                    6
                ),

            'evidence' => [

                'Account ownership information.',

                'Relevant screenshots.',

                'Clear explanation of the problem.',

                'Relevant account information.',
            ],

            'warnings' => [

                'Do not provide false information.',

                'Do not share passwords or recovery codes.',

                'Do not use unofficial recovery services.',
            ],
        ];
    }

    /**
     * Validate and normalize AI response.
     *
     * IMPORTANT:
     * The AI-provided recommended_action is ignored.
     *
     * The application-provided authoritative action is always used.
     */
    protected function validate(
        array $data,
        string $authoritativeAction
    ): array {

        $allowedActions = [
            'submit_appeal',
            'secure_account',
            'recover_account',
            'provide_more_information',
        ];

        /*
        |--------------------------------------------------------------------------
        | Safety check
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                $authoritativeAction,
                $allowedActions,
                true
            )
        ) {

            $authoritativeAction =
                'provide_more_information';
        }

        /*
        |--------------------------------------------------------------------------
        | Clean steps
        |--------------------------------------------------------------------------
        */

        $steps = $this->cleanList(
            $data['steps'] ?? []
        );

        /*
        |--------------------------------------------------------------------------
        | Clean evidence
        |--------------------------------------------------------------------------
        */

        $evidence = $this->cleanList(
            $data['evidence'] ?? []
        );

        /*
        |--------------------------------------------------------------------------
        | Clean warnings
        |--------------------------------------------------------------------------
        */

        $warnings = $this->cleanList(
            $data['warnings'] ?? []
        );

        /*
        |--------------------------------------------------------------------------
        | Ensure minimum useful content
        |--------------------------------------------------------------------------
        |
        | If AI returns too little information, throw an exception.
        |
        | The generate() method will then use the safe local fallback.
        |
        */

        if (
            count($steps) < 3
        ) {

            throw new RuntimeException(
                'AI returned insufficient recovery steps.'
            );
        }

        if (
            count($evidence) < 2
        ) {

            throw new RuntimeException(
                'AI returned insufficient evidence guidance.'
            );
        }

        if (
            count($warnings) < 2
        ) {

            throw new RuntimeException(
                'AI returned insufficient safety warnings.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Return normalized guidance
        |--------------------------------------------------------------------------
        */

        return [

            /*
            |--------------------------------------------------------------------------
            | IMPORTANT:
            | Never use $data['recommended_action'] here.
            |--------------------------------------------------------------------------
            */

            'recommended_action' =>
                $authoritativeAction,

            'steps' =>
                array_slice(
                    $steps,
                    0,
                    6
                ),

            'evidence' =>
                array_slice(
                    $evidence,
                    0,
                    6
                ),

            'warnings' =>
                array_slice(
                    $warnings,
                    0,
                    5
                ),
        ];
    }

    /**
     * Make sure a value is a clean array of strings.
     */
    protected function cleanList(
        mixed $value
    ): array {

        if (!is_array($value)) {
            return [];
        }

        return collect($value)
            ->filter(
                fn ($item) =>
                    is_string($item)
            )
            ->map(
                fn ($item) =>
                    trim($item)
            )
            ->filter()
            ->values()
            ->all();
    }
}