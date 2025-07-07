<?php

use Botble\Base\Facades\AdminHelper;
use Botble\Payment\Http\Controllers\PaymentLogController;
use Illuminate\Support\Facades\Route;

AdminHelper::registerRoutes(function (): void {
    Route::group(['prefix' => 'payments/logs', 'as' => 'payments.logs.', 'permission' => 'payments.logs'], function (): void {
        Route::match(['GET', 'POST'], '', [PaymentLogController::class, 'index'])->name('index');
        Route::get('{paymentLog}', [PaymentLogController::class, 'show'])->name('show');
        Route::delete('{paymentLog}', [PaymentLogController::class, 'destroy'])->name('destroy');
    });

    Route::group(['namespace' => 'Botble\Payment\Http\Controllers'], function (): void {
        Route::group(['prefix' => 'payments/methods', 'permission' => 'payments.settings'], function (): void {
            Route::get('', [
                'as' => 'payments.methods',
                'uses' => 'PaymentController@methods',
            ]);

            Route::put('settings', [
                'as' => 'payments.settings',
                'uses' => 'PaymentController@updateSettings',
                'middleware' => 'preventDemo',
            ]);

            Route::post('', [
                'as' => 'payments.methods.post',
                'uses' => 'PaymentController@updateMethods',
                'middleware' => 'preventDemo',
            ]);

            Route::post('update-status', [
                'as' => 'payments.methods.update.status',
                'uses' => 'PaymentController@updateMethodStatus',
                'middleware' => 'preventDemo',
            ]);
        });

        Route::group(['prefix' => 'payments/transactions', 'as' => 'payment.'], function (): void {
            Route::resource('', 'PaymentController')->parameters(['' => 'payment'])->only(['index', 'destroy']);

            Route::get('{payment}', [
                'as' => 'show',
                'uses' => 'PaymentController@show',
                'permission' => 'payment.index',
            ]);

            Route::put('{payment}', [
                'as' => 'update',
                'uses' => 'PaymentController@update',
                'permission' => 'payment.index',
            ]);

            Route::get('refund-detail/{id}/{refundId}', [
                'as' => 'refund-detail',
                'uses' => 'PaymentController@getRefundDetail',
                'permission' => 'payment.index',
            ]);
        });
    });
});
