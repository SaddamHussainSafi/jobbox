<?php

namespace Botble\Payment\Models;

use Botble\Base\Models\BaseModel;
use Botble\Payment\Enums\PaymentMethodEnum;

class PaymentLog extends BaseModel
{
    protected $table = 'payment_logs';

    protected $fillable = [
        'payment_method',
        'request',
        'response',
        'ip_address',
    ];

    protected $casts = [
        'payment_method' => PaymentMethodEnum::class,
        'request' => 'array',
        'response' => 'array',
    ];
}
