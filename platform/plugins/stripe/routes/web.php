<?php

use Botble\Stripe\Http\Controllers\StripeController;
use Illuminate\Support\Facades\Route;

Route::prefix('payment/stripe')
    ->name('payments.stripe.')
    ->group(function (): void {
        Route::post('webhook', [StripeController::class, 'webhook'])->name('webhook');

        Route::middleware(['web', 'core'])->group(function (): void {
            Route::get('success', [StripeController::class, 'success'])->name('success');
            Route::get('error', [StripeController::class, 'error'])->name('error');
        });
    });
