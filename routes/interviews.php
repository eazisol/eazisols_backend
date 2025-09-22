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

// Send email to interview's email address
Route::post('interviews/{interview}/send-mail', [InterviewsController::class, 'sendMail'])
    ->name('interviews.sendMail');

// Send Slack message for interview
Route::post('interviews/{interview}/send-slack', [InterviewsController::class, 'sendSlack'])
    ->name('interviews.sendSlack');
