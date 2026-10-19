<?php

namespace App\Http\Controllers;

use App\Jobs\AnalyzeCaseJob;
use App\Models\RecoveryCase;
use App\Services\RecoveryRecommendationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use App\Services\GuidanceService;

class AnalysisController extends Controller
{
    /**
     * Show the Analyze page.
     */
    public function show(
    RecoveryCase $case,
    RecoveryRecommendationService $recommendationService,
    GuidanceService $guidanceService
): View {
    abort_unless(
        $case->user_id === auth()->id(),
        403
    );

    Gate::authorize('view', $case);

    $case->load([
        'screenshots.analysis',
        'analyses',
        'evidence',
        'followups',
    ]);

    $analysis = $case->analyses()
        ->latest()
        ->first();

    $recommendation = $recommendationService->recommend($case);

    $guidance = $guidanceService->getGuidance($case);

    return view('analyze.index', [
        'case' => $case,
        'analysis' => $analysis,
        'recommendation' => $recommendation,
        'guidance' => $guidance,
    ]);
}

    /**
     * Start asynchronous case analysis.
     */
public function analyze(
    RecoveryCase $case
): RedirectResponse {

    abort_unless(
        $case->user_id === auth()->id(),
        403
    );

    Gate::authorize('update', $case);

    $case->update([
        'status' => 'analyzing',
        'analysis_progress' => 0,
        'analysis_step' => 'reading_inputs',
    ]);

    AnalyzeCaseJob::dispatch($case);

    return redirect()->route(
        'case.analyze',
        $case
    );
}

   public function status(RecoveryCase $case)
{
    abort_unless(
        $case->user_id === auth()->id(),
        403
    );

    Gate::authorize('view', $case);

    return response()->json([
        'status' => $case->status,
        'progress' => $case->analysis_progress,
        'step' => $case->analysis_step,
    ]);
}
}