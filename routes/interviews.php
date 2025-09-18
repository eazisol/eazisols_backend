<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Interviews\InterviewsController;

/*
|--------------------------------------------------------------------------
| Interview Routes
|--------------------------------------------------------------------------
|
| Here is where you can register interview related routes for your application.
|
*/

Route::resource('interviews', InterviewsController::class);

// Print single interview as HR Evaluation form
Route::get('interviews/{interview}/print', [InterviewsController::class, 'print'])
    ->name('interviews.print');
