<?php

use Illuminate\Support\Facades\Route;
use Modules\Reporting\Http\Controllers\CustomerController;
use Modules\Reporting\Http\Controllers\ProductController;
use Modules\Reporting\Http\Controllers\SaleController;

/**
 * Reporting routes.
 */
Route::prefix('reporting')->group(function () {
    /**
     * Customer routes.
     */
    Route::controller(CustomerController::class)->prefix('customers')->group(function () {
        Route::get('', 'index')->name('admin.reporting.customers.index');

        Route::get('stats', 'stats')->name('admin.reporting.customers.stats');

        Route::get('export', 'export')->name('admin.reporting.customers.export');

        Route::get('view', 'view')->name('admin.reporting.customers.view');

        Route::get('view/stats', 'viewStats')->name('admin.reporting.customers.view.stats');
    });

    /**
     * Product routes.
     */
    Route::controller(ProductController::class)->prefix('products')->group(function () {
        Route::get('', 'index')->name('admin.reporting.products.index');

        Route::get('stats', 'stats')->name('admin.reporting.products.stats');

        Route::get('export', 'export')->name('admin.reporting.products.export');

        Route::get('view', 'view')->name('admin.reporting.products.view');

        Route::get('view/stats', 'viewStats')->name('admin.reporting.products.view.stats');
    });

    /**
     * Sale routes.
     */
    Route::controller(SaleController::class)->prefix('sales')->group(function () {
        Route::get('', 'index')->name('admin.reporting.sales.index');

        Route::get('stats', 'stats')->name('admin.reporting.sales.stats');

        Route::get('export', 'export')->name('admin.reporting.sales.export');

        Route::get('view', 'view')->name('admin.reporting.sales.view');

        Route::get('view/stats', 'viewStats')->name('admin.reporting.sales.view.stats');
    });
});
