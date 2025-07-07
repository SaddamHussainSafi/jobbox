<?php

namespace Botble\Razorpay\Services\Gateways;

use Botble\Razorpay\Services\Abstracts\RazorpayPaymentAbstract;
use Illuminate\Http\Request;

class RazorpayPaymentService extends RazorpayPaymentAbstract
{
    public function makePayment(Request $request)
    {
    }

    public function afterMakePayment(Request $request)
    {
    }

    public function isValidToProcessCheckout(): bool
    {
        return apply_filters('razorpay_is_valid_to_process_checkout', true);
    }

    public function getOrderNotes(): array
    {
        return apply_filters('razorpay_order_notes', []);
    }

    public function redirectToCheckoutPage(array $data): void
    {
        echo view('plugins/razorpay::form', [
            'data' => $data,
            'action' => 'https://api.razorpay.com/v1/checkout/embedded',
        ]);

        exit();
    }
}
