<?php

namespace App\Http\Controllers;

use App\Models\RecoveryCase;
use App\Services\RecoveryRecommendationService;

class EscalationController extends Controller
{
    /**
     * Show the Escalation page.
     */
    public function show(
        RecoveryCase $case,
        RecoveryRecommendationService $recommendationService
    ) {
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