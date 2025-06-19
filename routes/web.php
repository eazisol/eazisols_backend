<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard')->middleware('auth');


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/admin/logout', [LoginController::class, 'logout'])->name('admin.logout');//login controller logout method

/*
|--------------------------------------------------------------------------
| Module Routes
|--------------------------------------------------------------------------
*/

// Include module route files
Route::middleware(['web', 'auth'])->group(function () {
    require __DIR__.'/careers.php';
    require __DIR__.'/applied_jobs.php';
    require __DIR__.'/blogs.php';
    require __DIR__.'/queries.php';
    require __DIR__.'/case_studies.php';
});
