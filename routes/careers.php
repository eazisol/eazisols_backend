<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Career\CareerController;

/*
|--------------------------------------------------------------------------
| Career Routes
|--------------------------------------------------------------------------
|
| Here is where you can register career related routes for your application.
|
*/

Route::resource('careers', CareerController::class);
Route::patch('careers/{career}/toggle-featured', [CareerController::class, 'toggleFeatured'])->name('careers.toggle-featured');
Route::get('careers/restore/{id}', [CareerController::class, 'restore'])->name('careers.restore'); 