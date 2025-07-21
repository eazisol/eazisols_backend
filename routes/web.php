<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Query\QueryController;
use App\Http\Controllers\AppliedJob\AppliedJobController;
use App\Http\Controllers\Employee\EmployeeController;

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
    return redirect()->route('login');
});

Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard')->middleware('auth');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::post('/admin/logout', [LoginController::class, 'logout'])->name('admin.logout');//login controller logout method

// Public routes for query submissions
Route::post('/contact-us', [QueryController::class, 'storeContactQuery'])->name('queries.contact.store');
Route::post('/cost-calculator', [QueryController::class, 'storeCostCalculatorQuery'])->name('queries.cost-calculator.store');

// Public API route for job applications
Route::post('/api/apply-for-job', [AppliedJobController::class, 'applyJob'])->name('applied-jobs.applyJob');

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
    require __DIR__.'/blogs.php';
    require __DIR__.'/queries.php';
    require __DIR__.'/case_studies.php';
    require __DIR__.'/categories.php';
    require __DIR__.'/locations.php';
    
    // User management routes
    Route::resource('users', App\Http\Controllers\UserController::class);
    
    // Attendance routes
    Route::get('/attendance', [App\Http\Controllers\AttendanceController::class, 'index'])->name('attendances.index');
    Route::get('/attendance/dashboard', [App\Http\Controllers\AttendanceController::class, 'dashboard'])->name('attendances.dashboard');
    Route::post('/attendance/check-in', [App\Http\Controllers\AttendanceController::class, 'checkIn'])->name('attendances.check-in');
    Route::post('/attendance/check-out', [App\Http\Controllers\AttendanceController::class, 'checkOut'])->name('attendances.check-out');
    Route::post('/attendance/mark', [App\Http\Controllers\AttendanceController::class, 'markAttendance'])->name('attendances.mark');
    Route::post('/attendance/import', [App\Http\Controllers\AttendanceController::class, 'import'])->name('attendances.import');
    Route::post('/attendance/add-public-holiday', [App\Http\Controllers\AttendanceController::class, 'addPublicHoliday'])->name('attendances.add_public_holiday');
    Route::get('/attendance/report', [App\Http\Controllers\AttendanceController::class, 'report'])->name('attendances.report');

    // Leave routes
    Route::get('/leaves/history', [App\Http\Controllers\LeaveController::class, 'history'])->name('leaves.history');
    Route::resource('leaves', App\Http\Controllers\LeaveController::class);
    Route::put('/leaves/{leave}/status', [App\Http\Controllers\LeaveController::class, 'updateStatus'])->name('leaves.update-status');
    Route::get('/leaves-calendar', [App\Http\Controllers\LeaveController::class, 'calendar'])->name('leaves.calendar');
    
    // Settings routes
    Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/test-email', [App\Http\Controllers\SettingsController::class, 'testEmail'])->name('settings.test-email');
    
    // Protected applied jobs routes (admin access only)
    Route::get('/applied-jobs', [AppliedJobController::class, 'index'])->name('applied-jobs.index');
    Route::get('/applied-jobs/{appliedJob}', [AppliedJobController::class, 'show'])->name('applied-jobs.show');
    Route::put('/applied-jobs/{appliedJob}/status', [AppliedJobController::class, 'updateStatus'])->name('applied-jobs.update-status');
    Route::delete('/applied-jobs/{appliedJob}', [AppliedJobController::class, 'destroy'])->name('applied-jobs.destroy');

    // Employee management routes
    Route::resource('employees', EmployeeController::class);
    // Department management routes
    Route::resource('departments', App\Http\Controllers\DepartmentController::class);
    // Only allow create, store, edit, update, destroy for designations (no index or show)
    Route::resource('designations', App\Http\Controllers\DesignationController::class)->except(['index', 'show']);
});

// Roles and Permissions routes
Route::group(['middleware' => ['auth']], function () {
    Route::resource('roles', 'App\Http\Controllers\RoleController');
    Route::resource('permissions', 'App\Http\Controllers\PermissionController');
});


