<?php

namespace App\Http\Controllers;

use App\Models\RecoveryCase;
use App\Services\RecoveryRecommendationService;
use Illuminate\Support\Facades\Gate;

class EscalationController extends Controller
{
    /**
     * Show the Escalation page.
     */
    public function show(
        RecoveryCase $case,
        RecoveryRecommendationService $recommendationService
    ) {
   
        Gate::authorize('view', $case);        

        $case->load([
            'evidence',
            'analyses',
            'appeals',
        ]);

        $recommendation =
            $recommendationService->recommend($case);

        return view('escalate.index', [
            'case' => $case,
            'recommendation' => $recommendation,
        ]);
    }
}