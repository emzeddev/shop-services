<?php

use Illuminate\Support\Facades\Route;
use Modules\Notification\Http\Controllers\NotificationController;


/**
 * Notification routes.
 */
Route::controller(NotificationController::class)->group(function () {
    Route::get('notifications', 'index')->name('admin.notification.index');

    Route::get('get-notifications', 'getNotifications')->name('admin.notification.get_notification');

    Route::get('viewed-notifications/{orderId}', 'viewedNotifications')->name('admin.notification.viewed_notification');

    Route::post('read-all-notifications', 'readAllNotifications')->name('admin.notification.read_all');
});