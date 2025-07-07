<?php

return [
    [
        'name' => 'Payments',
        'flag' => 'payment.index',
    ],
    [
        'name' => 'Settings',
        'flag' => 'payments.settings',
        'parent_flag' => 'payment.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'payment.destroy',
        'parent_flag' => 'payment.index',
    ],
    [
        'name' => 'Payment Logs',
        'flag' => 'payments.logs',
        'parent_flag' => 'payment.index',
    ],
    [
        'name' => 'View',
        'flag' => 'payments.logs.show',
        'parent_flag' => 'payments.logs',
    ],
    [
        'name' => 'Delete',
        'flag' => 'payments.logs.destroy',
        'parent_flag' => 'payments.logs',
    ],
];
