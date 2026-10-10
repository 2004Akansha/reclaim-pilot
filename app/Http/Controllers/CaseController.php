<?php

namespace App\Http\Controllers;

use App\Models\RecoveryCase;
use Illuminate\Http\Request;
use App\Services\ScreenshotAnalysisService;


class CaseController extends Controller
{
    /**
     * Show the Diagnose page.
     */
    public function create()
    {
        return view('diagnose.index');
    }


    /**
     * Store a new recovery case.
     */
    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate form data
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'issue_type' => [
                'required',
                'string',
                'in:account_disabled,hacked_account,cant_login,other',
            ],

            'description' => [
                'nullable',
                'string',
                'max:500',
            ],

            'username' => [
                'nullable',
                'string',
                'max:255',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'account_type' => [
                'nullable',
                'string',
                'in:personal,business,creator',
            ],

            /*
            |--------------------------------------------------------------------------
            | Screenshot validation
            |--------------------------------------------------------------------------
            */

            'screenshots' => [
                'nullable',
                'array',
                'max:5',
            ],

            'screenshots.*' => [
                'file',
                'mimes:jpg,jpeg,png,webp,pdf',
                'max:10240',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Create recovery case
        |--------------------------------------------------------------------------
        */

        $case = RecoveryCase::create([
            'issue_type' => $validated['issue_type'],

            'description' => $validated['description'] ?? null,

            'username' => $validated['username'] ?? null,

            'email' => $validated['email'] ?? null,

            'account_type' => $validated['account_type'] ?? null,

            'status' => 'diagnosing',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Save uploaded screenshots
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('screenshots')) {

            foreach ($request->file('screenshots') as $file) {

                $path = $file->store(
                    'screenshots',
                    'public'
                );

                $case->screenshots()->create([
                    'file_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Create default evidence checklist
        |--------------------------------------------------------------------------
        */

        $case->evidence()->createMany([

            [
                'title' => 'Account ownership proof',
                'description' => 'Provide proof that you own the account.',
                'completed' => false,
            ],

            [
                'title' => 'Disabled account screenshot',
                'description' => 'Upload a screenshot showing the account issue.',
                'completed' => false,
            ],

            [
                'title' => 'Recent login details',
                'description' => 'Provide recent device or login information.',
                'completed' => false,
            ],

            [
                'title' => 'Clear explanation',
                'description' => 'Explain clearly what happened.',
                'completed' => false,
            ],

            [
                'title' => 'Additional context',
                'description' => 'Add any other useful information.',
                'completed' => false,
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Redirect to Analyze page
        |--------------------------------------------------------------------------
        */

        return redirect()->route(
            'case.analyze',
            $case
        );
    }

    /**
 * Analyze uploaded screenshots using Vision AI.
 *
 * Temporary testing endpoint.
 */
public function analyzeScreenshots(
    RecoveryCase $case,
    ScreenshotAnalysisService $service
) {
    foreach ($case->screenshots as $screenshot) {

        // Do not analyze the same screenshot twice.
        if ($screenshot->analysis) {
            continue;
        }

        try {

            $result = $service->analyze(
                $screenshot
            );

            $screenshot->analysis()->create([

                'detected_issue' =>
                    $result['detected_issue'],

                'visible_message' =>
                    $result['visible_message'],

                'possible_reason' =>
                    $result['possible_reason'],

                'confidence' =>
                    $result['confidence'],

                'raw_analysis' =>
                    $result['raw_analysis'],
            ]);

        } catch (\Throwable $e) {

            return back()->with(
                'error',
                'Screenshot analysis failed: ' .
                $e->getMessage()
            );
        }
    }

    return back()->with(
        'success',
        'Screenshots analyzed successfully.'
    );
}
}