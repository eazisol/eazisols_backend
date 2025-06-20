<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Query\QueryController;

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

// Public routes for query submissions
Route::post('/contact-us', [QueryController::class, 'storeContactQuery'])->name('queries.contact.store');
Route::post('/cost-calculator', [QueryController::class, 'storeCostCalculatorQuery'])->name('queries.cost-calculator.store');

// Email preview routes (only for development environment)
if (app()->environment('local')) {
    Route::get('/email/preview/query-response', [\App\Http\Controllers\Query\EmailPreviewController::class, 'previewResponseEmail'])->name('email.preview.query-response');
    Route::get('/email/preview/query-status/{status?}', [\App\Http\Controllers\Query\EmailPreviewController::class, 'previewStatusEmail'])->name('email.preview.query-status');
}

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
    
    // Settings routes
    Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/test-email', [App\Http\Controllers\SettingsController::class, 'testEmail'])->name('settings.test-email');
});
