<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CaseController;
use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\RecoveryController;
use App\Http\Controllers\EscalationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EvidenceController;


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
|
| These routes can be accessed without logging in.
|
*/


/*
|--------------------------------------------------------------------------
| Home Page
|--------------------------------------------------------------------------
|
| Send visitors to the dashboard.
|
| Because /dashboard is protected by auth middleware,
| a logged-out user will be redirected to the login page.
|
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});


/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
|
| Everything inside this group requires the user to be logged in.
|
| Laravel's "auth" middleware checks the authentication session.
|
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | Diagnose
    |--------------------------------------------------------------------------
    |
    | Step 1 of the recovery workflow.
    |
    */

    Route::get('/case/create', [CaseController::class, 'create'])
        ->name('case.create');

    Route::post('/case', [CaseController::class, 'store'])
        ->name('case.store');


    /*
    |--------------------------------------------------------------------------
    | Analyze
    |--------------------------------------------------------------------------
    |
    | Step 2 of the recovery workflow.
    |
    */

    Route::get('/case/{case}/analyze', [AnalysisController::class, 'show'])
        ->name('case.analyze');

    Route::post('/case/{case}/analyze', [AnalysisController::class, 'analyze'])
        ->name('case.analyze.process');


    /*
    |--------------------------------------------------------------------------
    | Prepare
    |--------------------------------------------------------------------------
    |
    | Step 3 of the recovery workflow.
    |
    */

    Route::get('/case/{case}/prepare', [RecoveryController::class, 'show'])
        ->name('case.prepare');

    Route::post('/case/{case}/appeal', [RecoveryController::class, 'generateAppeal'])
        ->name('case.appeal.generate');


    /*
    |--------------------------------------------------------------------------
    | Escalate
    |--------------------------------------------------------------------------
    |
    | Step 4 of the recovery workflow.
    |
    */

    Route::get('/case/{case}/escalate', [EscalationController::class, 'show'])
        ->name('case.escalate');

    Route::post(
        '/case/{case}/evidence/{evidence}/upload',
        [EvidenceController::class, 'upload']
    )->name('case.evidence.upload');

    Route::get('/debug-php-time', function () {
        return [
            'max_execution_time' => ini_get('max_execution_time'),
            'max_input_time' => ini_get('max_input_time'),
            'memory_limit' => ini_get('memory_limit'),
        ];
    });

    Route::get(
        '/case/{case}/analysis-status',
        [AnalysisController::class, 'status']
    )->name('case.analysis.status');


});

Route::get('/test-ollama', function (
    \App\Services\AI\AIClient $client
) {
    return response()->json([
        'response' => $client->generate(
            'You are a helpful assistant. Return only JSON.',
            'Return JSON with a greeting field containing hello.',
            true
        ),
    ]);
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
| These routes are provided by Laravel Breeze / your authentication setup.
|
| Examples:
|
| /login
| /register
| /logout
| /forgot-password
| /reset-password
|
*/

require __DIR__.'/auth.php';