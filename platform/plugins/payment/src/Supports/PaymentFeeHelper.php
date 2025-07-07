<?php

namespace Botble\Payment\Supports;

use Botble\Payment\Enums\PaymentFeeTypeEnum;

class PaymentFeeHelper
{
    public static function calculateFee(string $paymentMethod, float $orderAmount): float
    {
        $feeValue = (float) get_payment_setting('fee', $paymentMethod, 0);
        $feeType = get_payment_setting('fee_type', $paymentMethod, PaymentFeeTypeEnum::FIXED);

        if ($feeType === PaymentFeeTypeEnum::PERCENTAGE) {
            return $orderAmount * ($feeValue / 100);
        }

        return $feeValue;
    }
}
