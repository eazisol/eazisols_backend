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