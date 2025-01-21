<?php

use Illuminate\Support\Facades\Route;
use Modules\Category\Http\Controllers\CategoryController;

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

Route::controller(CategoryController::class)->prefix('categories')->group(function () {
    Route::get('', 'index')->name('admin.catalog.categories.index');

    // Route::get('create', 'create')->name('admin.catalog.categories.create');

    // Route::post('create', 'store')->name('admin.catalog.categories.store');

    // Route::get('edit/{id}', 'edit')->name('admin.catalog.categories.edit');

    // Route::put('edit/{id}', 'update')->name('admin.catalog.categories.update');

    // Route::delete('edit/{id}', 'destroy')->name('admin.catalog.categories.delete');

    // Route::post('mass-delete', 'massDestroy')->name('admin.catalog.categories.mass_delete');

    // Route::post('mass-update', 'massUpdate')->name('admin.catalog.categories.mass_update');

    // Route::get('search', 'search')->name('admin.catalog.categories.search');

    // Route::get('tree', 'tree')->name('admin.catalog.categories.tree');
});
