<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReminderController;

/*
|--------------------------------------------------------------------------
| Reminder Routes
|--------------------------------------------------------------------------
|
| Here are the routes for the monthly reminder system.
|
*/

Route::prefix('reminders')->name('reminders.')->group(function () {
    Route::get('/', [ReminderController::class, 'index'])->name('index');
    Route::get('/create', [ReminderController::class, 'create'])->name('create');
    Route::post('/', [ReminderController::class, 'store'])->name('store');
    Route::get('/{reminder}', [ReminderController::class, 'show'])->name('show');
    Route::get('/{reminder}/edit', [ReminderController::class, 'edit'])->name('edit');
    Route::put('/{reminder}', [ReminderController::class, 'update'])->name('update');
    Route::patch('/{reminder}/toggle-status', [ReminderController::class, 'toggleStatus'])->name('toggle-status');
    Route::post('/{reminder}/test', [ReminderController::class, 'test'])->name('test');
    Route::delete('/{reminder}', [ReminderController::class, 'destroy'])->name('destroy');
});