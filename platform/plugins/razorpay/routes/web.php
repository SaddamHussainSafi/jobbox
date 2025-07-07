<?php

use Botble\Razorpay\Http\Controllers\RazorpayController;
use Illuminate\Support\Facades\Route;

Route::middleware(['core'])
    ->prefix('payment/razorpay')->name('payments.razorpay.')
    ->group(function (): void {
        Route::post('callback/{token}', [RazorpayController::class, 'callback'])->name('callback');

        Route::post('webhook', [RazorpayController::class, 'webhook'])
            ->name('webhook');
    });
