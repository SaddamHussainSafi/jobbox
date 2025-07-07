<?php

namespace Botble\Razorpay\Http\Controllers;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Models\Order;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Payment\Models\Payment;
use Botble\Payment\Supports\PaymentHelper;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\BadRequestError;
use Razorpay\Api\Errors\SignatureVerificationError;

class RazorpayController extends BaseController
{
    public function callback(
        string $token,
        Request $request,
        BaseHttpResponse $response
    ): BaseHttpResponse {
        if ($request->input('error.description')) {
            $message = $request->input('error.code') . ': ' . $request->input('error.description');

            return $this
                ->httpResponse()
                ->setNextUrl(PaymentHelper::getCancelURL($token) . '&error_message=' . $message)
                ->withInput()
                ->setMessage($message);
        }

        $chargeId = $request->input('razorpay_payment_id');

        if (! $chargeId) {
            return $response
                ->setNextUrl(PaymentHelper::getCancelURL($token))
                ->withInput()
                ->setMessage(__('Payment failed!'));
        }

        $orderId = $request->input('razorpay_order_id');

        $signature = $request->input('razorpay_signature');

        try {
            if ($orderId && $signature) {
                $status = PaymentStatusEnum::PENDING;

                $apiKey = get_payment_setting('key', RAZORPAY_PAYMENT_METHOD_NAME);
                $apiSecret = get_payment_setting('secret', RAZORPAY_PAYMENT_METHOD_NAME);

                $api = new Api($apiKey, $apiSecret);

                // @phpstan-ignore-next-line
                $api->utility->verifyPaymentSignature([
                    'razorpay_signature' => $signature,
                    'razorpay_payment_id' => $chargeId,
                    'razorpay_order_id' => $orderId,
                ]);

                do_action('payment_before_making_api_request', RAZORPAY_PAYMENT_METHOD_NAME, ['order_id' => $orderId]);

                // @phpstan-ignore-next-line
                $order = $api->order->fetch($orderId);

                $order = $order->toArray();

                do_action('payment_after_api_response', RAZORPAY_PAYMENT_METHOD_NAME, ['order_id' => $orderId], $order);

                $amount = $order['amount_paid'] / 100;

                $status = $order['status'] === 'paid' ? PaymentStatusEnum::COMPLETED : $status;

                $orderId = $request->input('order_id');

                if (! $orderId && class_exists(Order::class)) {
                    $orderId = Order::query()->where('token', $order['receipt'])->pluck('id')->all();
                }

                do_action(PAYMENT_ACTION_PAYMENT_PROCESSED, [
                    'amount' => $amount,
                    'currency' => $order['currency'],
                    'charge_id' => $chargeId,
                    'payment_channel' => RAZORPAY_PAYMENT_METHOD_NAME,
                    'status' => $status,
                    'order_id' => $orderId,
                    'customer_id' => $request->input('customer_id'),
                    'customer_type' => $request->input('customer_type'),
                ]);
            }
        } catch (SignatureVerificationError $exception) {
            BaseHelper::logError($exception);

            return $response
                ->setNextUrl(PaymentHelper::getCancelURL($token) . '&error_message=' . $exception->getMessage())
                ->withInput()
                ->setMessage($exception->getMessage());
        }

        return $response
            ->setNextUrl(PaymentHelper::getRedirectURL($token) . '?charge_id=' . $chargeId)
            ->setMessage(__('Checkout successfully!'));
    }

    public function webhook(Request $request)
    {
        if (
            $request->input('event') === 'order.paid'
            && $request->input('payload.order.entity.status') === 'paid'
        ) {
            $api = new Api(
                get_payment_setting('key', RAZORPAY_PAYMENT_METHOD_NAME),
                get_payment_setting('secret', RAZORPAY_PAYMENT_METHOD_NAME)
            );

            try {
                $orderId = $request->input('payload.payment.entity.order_id');

                do_action('payment_before_making_api_request', RAZORPAY_PAYMENT_METHOD_NAME, ['order_id' => $orderId]);

                // @phpstan-ignore-next-line
                $order = $api->order->fetch($orderId);

                do_action('payment_after_api_response', RAZORPAY_PAYMENT_METHOD_NAME, ['order_id' => $orderId], $order->toArray());

                $status = PaymentStatusEnum::PENDING;

                if ($order['status'] === 'paid') {
                    $status = PaymentStatusEnum::COMPLETED;
                }

                $chargeId = $request->input('payload.payment.entity.id');

                $payment = Payment::query()
                    ->where('charge_id', $chargeId)
                    ->first();

                if ($payment) {
                    $payment->status = $status;
                    $payment->save();

                    $orderId = $payment->order_id;
                } elseif (class_exists(Order::class)) {
                    $orderId = Order::query()->where('token', $order['receipt'])->pluck('id')->all();
                }

                if ($orderId) {
                    do_action(PAYMENT_ACTION_PAYMENT_PROCESSED, [
                        'charge_id' => $chargeId,
                        'order_id' => $orderId,
                        'status' => $status,
                        'payment_channel' => RAZORPAY_PAYMENT_METHOD_NAME,
                    ]);

                    return response('ok');
                }
            } catch (BadRequestError $exception) {
                BaseHelper::logError($exception);

                return response('invalid payload.', 400);
            }
        }
    }
}
