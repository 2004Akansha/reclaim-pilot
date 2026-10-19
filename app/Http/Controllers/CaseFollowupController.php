<?php

namespace App\Http\Controllers;

use App\Jobs\AnalyzeCaseJob;
use App\Models\RecoveryCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CaseFollowupController extends Controller
{
    /**
     * Store additional information and re-analyze the case.
     */
    public function store(Request $request, RecoveryCase $case)
    {
        /*
        |--------------------------------------------------------------------------
        | Security: make sure this case belongs to the logged-in user
        |--------------------------------------------------------------------------
        */
        abort_unless(
            $case->user_id === Auth::id(),
            403
        );

        /*
        |--------------------------------------------------------------------------
        | Validate follow-up message
        |--------------------------------------------------------------------------
        */
        $validated = $request->validate([
            'message' => [
                'required',
                'string',
                'min:5',
                'max:5000',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Save follow-up
        |--------------------------------------------------------------------------
        */
        $case->followups()->create([
            'message' => $validated['message'],
            'status' => 'pending',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Re-run the complete analysis workflow
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | AnalyzeCaseJob expects a RecoveryCase object,
        | not a case ID.
        |
        */
        AnalyzeCaseJob::dispatch($case);

        /*
        |--------------------------------------------------------------------------
        | Return to Analyze page
        |--------------------------------------------------------------------------
        */
        return redirect()
            ->route('case.analyze', $case)
            ->with(
                'success',
                'Your additional information was added. The case is being re-analyzed.'
            );
    }
}