<?php

namespace App\Http\Controllers;

use App\Jobs\AnalyzeCaseJob;
use App\Models\RecoveryCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AnalysisController extends Controller
{
    /**
     * Show the Analyze page.
     */
    public function show(
        RecoveryCase $case
    ): View {

        $case->load([
            'screenshots.analysis',
            'analyses',
        ]);

        $analysis = $case->analyses()
            ->latest()
            ->first();

        return view('analyze.index', [
            'case' => $case,
            'analysis' => $analysis,
        ]);
    }


    /**
     * Start asynchronous case analysis.
     */
    public function analyze(
    RecoveryCase $case
): RedirectResponse {

    $case->update([
        'status' => 'analyzing',
        'analysis_progress' => 0,
        'analysis_step' => 'starting',
    ]);

    AnalyzeCaseJob::dispatch($case);

    return redirect()->route(
        'case.analyze',
        $case
    );
}

    public function status(RecoveryCase $case)
    {
        return response()->json([
            'status' => $case->status,
            'progress' => $case->analysis_progress,
            'step' => $case->analysis_step,
        ]);
    }
}