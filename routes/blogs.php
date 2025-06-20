<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Blogs\BlogsController;

/*
|--------------------------------------------------------------------------
| Blog Routes
|--------------------------------------------------------------------------
|
| Here is where you can register blog related routes for your application.
|
*/

Route::resource('blogs', BlogsController::class);
Route::get('blogs/restore/{id}', [BlogsController::class, 'restore'])->name('blogs.restore'); 