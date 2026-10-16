<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the user's ReclaimPilot dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Get only the logged-in user's cases
        |--------------------------------------------------------------------------
        */

        $cases = $user->recoveryCases()
            ->latest()
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Dashboard statistics
        |--------------------------------------------------------------------------
        */

        $totalCases = $cases->count();

        $diagnosingCases = $cases
            ->where('status', 'diagnosing')
            ->count();

        $preparedCases = $cases
            ->where('status', 'prepared')
            ->count();

        $escalatedCases = $cases
            ->where('status', 'escalated')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Return dashboard
        |--------------------------------------------------------------------------
        */

        return view('dashboard', [
            'cases' => $cases,
            'totalCases' => $totalCases,
            'diagnosingCases' => $diagnosingCases,
            'preparedCases' => $preparedCases,
            'escalatedCases' => $escalatedCases,
        ]);
    }
}