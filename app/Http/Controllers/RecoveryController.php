<?php

namespace App\Http\Controllers;

use App\Models\RecoveryCase;
use App\Services\AppealGeneratorService;
use App\Services\RecoveryRecommendationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RecoveryController extends Controller
{
    /**
     * Show the Prepare Recovery Case page.
     */
    public function show(
        RecoveryCase $case,
        RecoveryRecommendationService $recommendationService
    ): View {

        /*
        |--------------------------------------------------------------------------
        | Load related data
        |--------------------------------------------------------------------------
        |
        | We load the data needed by the Prepare page.
        |
        */

        $case->load([
            'evidence',
            'analyses',
            'appeals',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Get latest analysis
        |--------------------------------------------------------------------------
        */

        $analysis = $case->analyses()
            ->latest()
            ->first();


        /*
        |--------------------------------------------------------------------------
        | Get recovery recommendation
        |--------------------------------------------------------------------------
        |
        | This service looks at the latest analysis and decides
        | what recovery action should be recommended.
        |
        */

        $recommendation =
            $recommendationService->recommend($case);


        /*
        |--------------------------------------------------------------------------
        | Get latest appeal
        |--------------------------------------------------------------------------
        */

        $appeal = $case->appeals()
            ->latest()
            ->first();


        /*
        |--------------------------------------------------------------------------
        | Send data to Prepare page
        |--------------------------------------------------------------------------
        */

        return view('prepare.index', [

            'case' => $case,

            'analysis' => $analysis,

            'recommendation' => $recommendation,

            'appeal' => $appeal,

        ]);
    }


    /**
     * Generate an appeal for the recovery case.
     */
    public function generateAppeal(
        RecoveryCase $case,
        AppealGeneratorService $appealGenerator
    ): RedirectResponse {

        try {

            /*
            |--------------------------------------------------------------------------
            | Generate appeal
            |--------------------------------------------------------------------------
            |
            | Currently your AppealGeneratorService generates the appeal.
            |
            | Later this service can call Ollama.
            |
            */

            $content =
                $appealGenerator->generate($case);


            /*
            |--------------------------------------------------------------------------
            | Save appeal
            |--------------------------------------------------------------------------
            */

            $case->appeals()->create([

                'content' => $content,

                'language' => 'English',

            ]);


            /*
            |--------------------------------------------------------------------------
            | Update case status
            |--------------------------------------------------------------------------
            */

            $case->update([

                'status' => 'prepared',

            ]);


            /*
            |--------------------------------------------------------------------------
            | Redirect back to Prepare page
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route('case.prepare', $case)
                ->with(
                    'success',
                    'Appeal draft generated successfully.'
                );


        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | Log the error
            |--------------------------------------------------------------------------
            |
            | report() sends the exception to Laravel's logging system.
            |
            */

            report($e);


            /*
            |--------------------------------------------------------------------------
            | Show friendly error to user
            |--------------------------------------------------------------------------
            */

            return back()
                ->with(
                    'error',
                    'We could not generate the appeal right now. Please try again.'
                );
        }
    }
}