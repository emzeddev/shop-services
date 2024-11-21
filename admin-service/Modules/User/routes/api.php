<?php

use Illuminate\Support\Facades\Route;


use Modules\User\Http\Controllers\UserController;
// use Webkul\Admin\Http\Controllers\User\ForgetPasswordController;
// use Webkul\Admin\Http\Controllers\User\ResetPasswordController;
// use Webkul\Admin\Http\Controllers\User\SessionController;

/**
 * Auth routes.
 */
Route::group(['prefix' => config('app.admin_url')], function () {
    /**
     * Redirect route.
    */

    Route::controller(UserController::class)->prefix('login')->group(function () {
        Route::post('', 'store')->name('admin.account.store');
    });

    /**
     * Forget password routes.
     */
    // Route::controller(ForgetPasswordController::class)->prefix('forget-password')->group(function () {
    //     Route::get('', 'create')->name('admin.forget_password.create');

    //     Route::post('', 'store')->name('admin.forget_password.store');
    // });

    /**
     * Reset password routes.
     */
    // Route::controller(ResetPasswordController::class)->prefix('reset-password')->group(function () {
    //     Route::get('{token}', 'create')->name('admin.reset_password.create');

    //     Route::post('', 'store')->name('admin.reset_password.store');
    // });
});
