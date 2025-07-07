<?php

namespace Botble\Payment\Supports;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Supports\Helper;
use Botble\Payment\Enums\PaymentMethodEnum;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Payment\Models\Payment;
use Botble\Payment\Models\PaymentLog;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class PaymentHelper
{
    public static function getRedirectURL(?string $checkoutToken = null): string
    {
        return apply_filters(PAYMENT_FILTER_REDIRECT_URL, $checkoutToken, BaseHelper::getHomepageUrl());
    }

    public static function getCancelURL(?string $checkoutToken = null): string
    {
        return apply_filters(PAYMENT_FILTER_CANCEL_URL, $checkoutToken, BaseHelper::getHomepageUrl());
    }

    public static function storeLocalPayment(array $args = [])
    {
        $data = [
            'user_id' => Auth::id() ?: 0,
            ...$args,
        ];

        $orderIds = (array) $data['order_id'];

        $payment = Payment::query()
            ->where('charge_id', $data['charge_id'])
            ->whereIn('order_id', $orderIds)
            ->first();

        if ($payment) {
            if ($payment->status != $data['status']) {
                $payment->status = $data['status'];
                $payment->save();
            }

            return false;
        }

        $paymentChannel = Arr::get($data, 'payment_channel', PaymentMethodEnum::COD);

        // Get payment fee using PaymentFeeHelper
        $paymentFee = 0;
        if ($paymentChannel) {
            $orderAmount = $data['amount'];
            $paymentFee = PaymentFeeHelper::calculateFee($paymentChannel, $orderAmount);
        }

        return Payment::query()
            ->create([
                'amount' => $data['amount'],
                'payment_fee' => $paymentFee,
                'currency' => $data['currency'],
                'charge_id' => $data['charge_id'],
                'order_id' => Arr::first($orderIds),
                'customer_id' => Arr::get($data, 'customer_id'),
                'customer_type' => Arr::get($data, 'customer_type'),
                'payment_channel' => $paymentChannel,
                'status' => Arr::get($data, 'status', PaymentStatusEnum::PENDING),
            ]);
    }

    public static function formatLog(
        array $input,
        string|int $line = '',
        string $function = '',
        string $class = ''
    ): array {
        return [
            ...$input,
            'user_id' => Auth::id() ?: 0,
            'ip' => Request::ip(),
            'line' => $line,
            'function' => $function,
            'class' => $class,
            'userAgent' => Request::userAgent(),
        ];
    }

    public static function defaultPaymentMethod(): string
    {
        return setting('default_payment_method', PaymentMethodEnum::COD);
    }

    public static function getAvailableCountries(string $paymentMethod): array
    {
        $json = get_payment_setting('available_countries', $paymentMethod);

        $countries = Helper::countries();

        if ($json === null || $json === '[]') {
            return $countries;
        }

        $selectedCountries = json_decode($json, true);

        if (empty($selectedCountries)) {
            return $countries;
        }

        return array_intersect_key($countries, array_flip($selectedCountries));
    }

    public static function getPaymentMethodRules(string $paymentMethod): array
    {
        return [
            get_payment_setting_key('name', $paymentMethod) => ['required', 'string', 'max:255'],
            get_payment_setting_key('description', $paymentMethod) => ['required', 'string'],
            get_payment_setting_key('logo', $paymentMethod) => ['nullable', 'string'],
            get_payment_setting_key('available_countries', $paymentMethod) => ['nullable', 'array'],
            sprintf('%s.*', get_payment_setting_key('available_countries', $paymentMethod)) => ['nullable', 'string'],
        ];
    }

    public static function log(string $paymentMethod, array $request = [], array $response = []): void
    {
        PaymentLog::query()->create([
            'payment_method' => $paymentMethod,
            'request' => $request,
            'response' => $response,
            'ip_address' => request()->ip(),
        ]);
    }
}
