<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Career\CareerController;
use App\Http\Controllers\Blogs\BlogsController;
use App\Http\Controllers\CaseStudy\CaseStudyController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Jobs/Careers API routes
Route::get('/careers', [CareerController::class, 'apiGetAll']);
Route::get('/careers/{career}', [CareerController::class, 'apiGetOne']);


// blogs
Route::get('/blogs', [BlogsController::class, 'apiGetAll']);
Route::get('/blogs/{id}', [BlogsController::class, 'apiGetOne']);

// case studies
Route::get('/case-studies', [CaseStudyController::class, 'apiGetAll']);
Route::get('/case-studies/{id}', [CaseStudyController::class, 'apiGetOne']);