<?php

namespace Botble\Payment\Enums;

use Botble\Base\Supports\Enum;

/**
 * @method static PaymentFeeTypeEnum FIXED()
 * @method static PaymentFeeTypeEnum PERCENTAGE()
 */
class PaymentFeeTypeEnum extends Enum
{
    public const FIXED = 'fixed';
    public const PERCENTAGE = 'percentage';

    public static $langPath = 'plugins/payment::payment.fee_types';

    public function toHtml(): string
    {
        return match ($this->value) {
            self::FIXED => trans('plugins/payment::payment.fee_types.fixed'),
            self::PERCENTAGE => trans('plugins/payment::payment.fee_types.percentage'),
            default => parent::toHtml(),
        };
    }
}
