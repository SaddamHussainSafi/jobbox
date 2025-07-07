<?php

namespace Botble\JobBoard\Http\Controllers\Fronts;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\JobBoard\Facades\JobBoardHelper;
use Botble\JobBoard\Models\Package;
use Botble\JobBoard\Services\CouponService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CouponController extends BaseController
{
    public function __construct()
    {
        $this->middleware(function (Request $request, Closure $next) {
            abort_if(! JobBoardHelper::isEnabledCreditsSystem() || ! $request->ajax(), 404);

            return $next($request);
        });
    }

    public function apply(Request $request, CouponService $couponService): BaseHttpResponse
    {
        $request->validate([
            'coupon_code' => ['required', 'string'],
        ]);

        $coupon = $couponService->getCouponByCode($request->input('coupon_code'));

        if ($coupon === null) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('This coupon is invalid!'));
        }

        $discountAmount = $couponService->getDiscountAmount(
            $coupon->type->getValue(),
            $coupon->value,
            Session::get('cart_total')
        );

        Session::put('applied_coupon_code', $coupon->code);
        Session::put('coupon_discount_amount', $discountAmount);

        return $this
            ->httpResponse()
            ->setMessage(__('Applied coupon ":code" successfully!', ['code' => $coupon->code]));
    }

    public function remove(): BaseHttpResponse
    {
        if (! Session::has('applied_coupon_code')) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('This coupon is not used yet!'));
        }

        Session::forget('applied_coupon_code');
        Session::forget('coupon_discount_amount');

        return $this
            ->httpResponse()
            ->setMessage(__('Removed coupon :code successfully!', ['code' => session('applied_coupon_code')]));
    }

    public function refresh(string $id, CouponService $service): BaseHttpResponse
    {
        $package = Package::query()->findOrFail($id);

        $totalAmount = $service->getAmountAfterDiscount(
            Session::get('coupon_discount_amount', 0),
            $package->price
        );

        return $this
            ->httpResponse()
            ->setData(view('plugins/job-board::coupons.partials.form', compact('package', 'totalAmount'))->render());
    }
}
