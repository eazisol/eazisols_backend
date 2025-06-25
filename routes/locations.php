<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Locations\LocationsController;

/*
|--------------------------------------------------------------------------
| Location Routes
|--------------------------------------------------------------------------
|
| Here is where you can register location related routes for your application.
|
*/

Route::resource('locations', LocationsController::class); 