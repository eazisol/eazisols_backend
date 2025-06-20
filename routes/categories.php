<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Categories\CategoriesController;

/*
|--------------------------------------------------------------------------
| Category Routes
|--------------------------------------------------------------------------
|
| Here is where you can register category related routes for your application.
|
*/

Route::resource('categories', CategoriesController::class); 