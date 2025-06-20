<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppliedJob\AppliedJobController;

/*
|--------------------------------------------------------------------------
| Applied Jobs Routes
|--------------------------------------------------------------------------
|
| Here is where you can register applied jobs related routes for your application.
|
*/

// Admin routes (protected by auth middleware)
Route::get('/applied-jobs', [AppliedJobController::class, 'index'])->name('applied-jobs.index');
Route::get('/applied-jobs/{appliedJob}', [AppliedJobController::class, 'show'])->name('applied-jobs.show');
Route::put('/applied-jobs/{appliedJob}/status', [AppliedJobController::class, 'updateStatus'])->name('applied-jobs.update-status');
Route::delete('/applied-jobs/{appliedJob}', [AppliedJobController::class, 'destroy'])->name('applied-jobs.destroy');

// API routes (public, no auth required)
Route::post('/api/apply-for-job', [AppliedJobController::class, 'apiStore'])->name('api.apply-job'); 