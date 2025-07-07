<?php

namespace Botble\JobBoard\Services;

use Botble\JobBoard\Enums\CouponTypeEnum;
use Botble\JobBoard\Models\Coupon;
use Botble\JobBoard\Models\Package;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class CouponService
{
    protected ?Package $package = null;

    public function getCouponByCode(string $code): Coupon|Model|null
    {
        return Coupon::query()
            ->where('code', $code)
            ->where(function (Builder $query): void {
                $query->whereNull('expires_date')
                    ->orWhere('expires_date', '>=', Carbon::now());
            })
            ->where(function (Builder $query): void {
                $query->whereNull('quantity')
                    ->orWhereColumn('quantity', '>', 'total_used');
            })
            ->first();
    }

    public function getDiscountAmount(string $type, float $value, float $amountTotal = 0): float
    {
        return match ($type) {
            CouponTypeEnum::PERCENTAGE => $value / 100 * $amountTotal,
            CouponTypeEnum::FIXED => $value,
            default => 0,
        };
    }

    public function getAmountAfterDiscount(float $discountAmount, float $amountTotal): float
    {
        return max($amountTotal - $discountAmount, 0);
    }

    public function forgotCouponSession(string|array $keys = []): void
    {
        Session::forget(array_merge((array) $keys, [
            'applied_coupon_code',
            'coupon_discount_amount',
            'cart_total',
        ]));
    }
}
