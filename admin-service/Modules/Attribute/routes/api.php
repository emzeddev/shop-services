<?php

use Illuminate\Support\Facades\Route;
use Modules\Attribute\Http\Controllers\AttributeController;
use Modules\Attribute\Http\Controllers\AttributeFamilyController;

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

Route::prefix('catalog')->group(function () {
    /**
     * Attributes routes.
     */
    Route::controller(AttributeController::class)->prefix('attributes')->group(function () {
        Route::get('', 'index')->name('admin.catalog.attributes.index');

        Route::get('{id}/options', 'getAttributeOptions')->name('admin.catalog.attributes.options');

        Route::post('create', 'store')->name('admin.catalog.attributes.store');

        Route::get('edit/{id}', 'edit')->name('admin.catalog.attributes.edit');

        Route::put('edit/{id}', 'update')->name('admin.catalog.attributes.update');

        Route::delete('edit/{id}', 'destroy')->name('admin.catalog.attributes.delete');

        Route::post('mass-delete', 'massDestroy')->name('admin.catalog.attributes.mass_delete');
    });

    /**
     * Attribute families routes.
     */
    Route::controller(AttributeFamilyController::class)->prefix('families')->group(function () {
        Route::get('', 'index')->name('admin.catalog.families.index');

        Route::get('create', 'create')->name('admin.catalog.families.create');

        Route::post('create', 'store')->name('admin.catalog.families.store');

        Route::get('edit/{id}', 'edit')->name('admin.catalog.families.edit');

        Route::put('edit/{id}', 'update')->name('admin.catalog.families.update');

        Route::delete('destroy/{id}', 'destroy')->name('admin.catalog.families.delete');
    });

});
