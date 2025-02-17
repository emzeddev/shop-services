<?php

use Illuminate\Support\Facades\Route;
use Modules\CartRule\Http\Controllers\CartRuleController;

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

Route::group([], function () {
    Route::resource('cartrule', CartRuleController::class)->names('cartrule');
});
