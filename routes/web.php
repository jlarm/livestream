<?php

use App\Http\Controllers\SubmissionController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::post('/', [SubmissionController::class, 'store'])
    ->middleware('throttle:3,1')
    ->name('submission.store');

Route::get('submissions/export', [SubmissionController::class, 'exportCsv'])
    ->middleware(['auth', 'verified'])->name('submissions.export');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
