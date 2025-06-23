<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CaseStudy\CaseStudyController;

/*
|--------------------------------------------------------------------------
| Case Study Routes
|--------------------------------------------------------------------------
|
| Here is where you can register case study related routes for your application.
|
*/

Route::resource('case_studies', CaseStudyController::class); 