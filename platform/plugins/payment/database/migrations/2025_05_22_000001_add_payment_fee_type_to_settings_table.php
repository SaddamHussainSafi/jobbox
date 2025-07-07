<?php

use Botble\Payment\Enums\PaymentFeeTypeEnum;
use Botble\Payment\Enums\PaymentMethodEnum;
use Botble\Setting\Facades\Setting;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    public function up(): void
    {
        foreach (PaymentMethodEnum::values() as $paymentMethod) {
            Setting::set('payment_' . $paymentMethod . '_fee_type', PaymentFeeTypeEnum::FIXED);
        }

        Setting::save();
    }

    public function down(): void
    {
        foreach (PaymentMethodEnum::values() as $paymentMethod) {
            Setting::delete('payment_' . $paymentMethod . '_fee_type');
        }

        Setting::save();
    }
};
