<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Query\QueryController;

/*
|--------------------------------------------------------------------------
| Query Routes
|--------------------------------------------------------------------------
|
| Here is where you can register query related routes for your application.
|
*/

// Admin routes (protected by auth middleware in web.php)
Route::get('/queries', [QueryController::class, 'index'])->name('queries.index');
Route::get('/queries/{query}', [QueryController::class, 'show'])->name('queries.show');
Route::put('/queries/{query}', [QueryController::class, 'update'])->name('queries.update');
Route::post('/queries/{query}/responses', [QueryController::class, 'storeResponse'])->name('queries.responses.store');
Route::get('/query-attachments/{attachment}/download', [QueryController::class, 'downloadAttachment'])->name('query.attachments.download');

// Public routes are now moved to web.php 