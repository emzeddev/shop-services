<?php

use Illuminate\Support\Facades\Route;


use Modules\Product\Http\Controllers\BundleController;
use Modules\Product\Http\Controllers\ConfigurableController;
use Modules\Product\Http\Controllers\DownloadableController;
use Modules\Product\Http\Controllers\GroupedController;
use Modules\Product\Http\Controllers\ProductController;
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


/**
 * Products routes.
 */
Route::controller(ProductController::class)->prefix('products')->group(function () {
        Route::get('/sync', 'sync')->name('admin.catalog.products.sync');

        // Route::get('', 'index')->name('admin.catalog.products.index');

        // Route::post('create', 'store')->name('admin.catalog.products.store');

        // Route::get('copy/{id}', 'copy')->name('admin.catalog.products.copy');

        // Route::get('edit/{id}', 'edit')->name('admin.catalog.products.edit');

        // Route::put('edit/{id}', 'update')->name('admin.catalog.products.update');

        // Route::delete('edit/{id}', 'destroy')->name('admin.catalog.products.delete');

        // Route::put('edit/{id}/inventories', 'updateInventories')->name('admin.catalog.products.update_inventories');

        // Route::post('upload-file/{id}', 'uploadLink')->name('admin.catalog.products.upload_link');

        // Route::post('upload-sample/{id}', 'uploadSample')->name('admin.catalog.products.upload_sample');

        // Route::post('mass-update', 'massUpdate')->name('admin.catalog.products.mass_update');

        // Route::post('mass-delete', 'massDestroy')->name('admin.catalog.products.mass_delete');

        // Route::controller(ConfigurableController::class)->group(function () {
        //     Route::get('{id}/configurable-options', 'options')->name('admin.catalog.products.configurable.options');
        // });

        // Route::controller(BundleController::class)->group(function () {
        //     Route::get('{id}/bundle-options', 'options')->name('admin.catalog.products.bundle.options');
        // });

        // Route::controller(GroupedController::class)->group(function () {
        //     Route::get('{id}/grouped-options', 'options')->name('admin.catalog.products.grouped.options');
        // });

        // Route::controller(DownloadableController::class)->group(function () {
        //     Route::get('{id}/downloadable-options', 'options')->name('admin.catalog.products.downloadable.options');
        // });

        // Route::get('search', 'search')->name('admin.catalog.products.search');

        // Route::get('{id}/{attribute_id}', 'download')->name('admin.catalog.products.file.download');
    });
