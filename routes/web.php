<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CaseController;
use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\RecoveryController;
use App\Http\Controllers\EscalationController;
use App\Http\Controllers\EvidenceController;


/*
|--------------------------------------------------------------------------
| Diagnose
|--------------------------------------------------------------------------
*/

// Show diagnose page
Route::get('/', [CaseController::class, 'create'])
    ->name('case.create');

// Submit diagnose form
Route::post('/case', [CaseController::class, 'store'])
    ->name('case.store');


/*
|--------------------------------------------------------------------------
| Analyze
|--------------------------------------------------------------------------
*/

// Show analyze page
Route::get('/case/{case}/analyze', [AnalysisController::class, 'show'])
    ->name('case.analyze');

// Start analysis
Route::post('/case/{case}/analyze', [AnalysisController::class, 'analyze'])
    ->name('case.analyze.process');

// Test screenshot AI analysis
// Route::post(
//     '/case/{case}/screenshots/analyze',
//     [CaseController::class, 'analyzeScreenshots']
// )->name('case.screenshots.analyze');

/*
|--------------------------------------------------------------------------
| Prepare
|--------------------------------------------------------------------------
*/

// Show prepare page
Route::get('/case/{case}/prepare', [RecoveryController::class, 'show'])
    ->name('case.prepare');

// Generate appeal
Route::post('/case/{case}/appeal', [RecoveryController::class, 'generateAppeal'])
    ->name('case.appeal.generate');


/*
|--------------------------------------------------------------------------
| Escalate
|--------------------------------------------------------------------------
*/

// Show escalation page
Route::get('/case/{case}/escalate', [EscalationController::class, 'show'])
    ->name('case.escalate');


/*
|--------------------------------------------------------------------------
| Evidence
|--------------------------------------------------------------------------
*/

// Mark evidence as completed
// Route::post(
//     '/case/{case}/evidence/{evidence}/complete',
//     [EvidenceController::class, 'complete']
// )->name('evidence.complete');

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