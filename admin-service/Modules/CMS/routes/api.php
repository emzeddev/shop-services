<?php

use Illuminate\Support\Facades\Route;
use Modules\CMS\Http\Controllers\PageController;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/

Route::controller(PageController::class)->prefix('cms')->group(function () {
    Route::get('/', 'index')->name('admin.cms.index');

    Route::get('create', 'create')->name('admin.cms.create');

    Route::post('create', 'store')->name('admin.cms.store');

    Route::get('edit/{id}', 'edit')->name('admin.cms.edit');

    Route::put('edit/{id}', 'update')->name('admin.cms.update');

    Route::delete('edit/{id}', 'delete')->name('admin.cms.delete');

    Route::post('mass-delete', 'massDelete')->name('admin.cms.mass_delete');
});
