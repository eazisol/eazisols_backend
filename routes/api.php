<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppliedJob\AppliedJobController;

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

// Public API routes (no auth required)
Route::post('/apply-for-job', [AppliedJobController::class, 'apiStore']);

// Include other API routes
// Route::prefix('v1')->group(function () {
//     require __DIR__.'/api/careers.php';
//     require __DIR__.'/api/blogs.php';
//     require __DIR__.'/api/queries.php';
//     require __DIR__.'/api/case_studies.php';
// });
