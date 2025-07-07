<?php

namespace Botble\JobBoard\Http\Controllers;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Facades\Assets;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Supports\Breadcrumb;
use Botble\JobBoard\Http\Requests\CouponRequest;
use Botble\JobBoard\Models\Coupon;
use Botble\JobBoard\Tables\CouponTable;
use Botble\JsValidation\Facades\JsValidator;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class CouponController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/job-board::job-board.name'))
            ->add(trans('plugins/job-board::coupon.name'), route('coupons.index'));
    }

    public function index(CouponTable $discountTable): View|JsonResponse
    {
        $this->pageTitle(trans('plugins/job-board::coupon.name'));

        return $discountTable->renderTable();
    }

    public function create(): View
    {
        Assets::usingVueJS()
            ->addStyles('timepicker')
            ->addScripts(['timepicker', 'form-validation'])
            ->addScriptsDirectly('vendor/core/plugins/job-board/js/coupon.js');

        $jsValidator = JsValidator::formRequest(CouponRequest::class);

        $coupon = new Coupon();

        return view('plugins/job-board::coupons.create', compact('jsValidator', 'coupon'));
    }

    public function store(CouponRequest $request): BaseHttpResponse
    {
        $coupon = Coupon::query()->create(array_merge($request->validated(), $request->has('never_expired') ? [] : [
            'expires_date' => $request->date('expires_date')->setTimeFrom($request->input('expires_time')),
        ]));

        event(new CreatedContentEvent(COUPON_MODULE_SCREEN_NAME, $request, $coupon));

        return $this
            ->httpResponse()
            ->setMessage(trans('plugins/job-board::coupon.created_message'))
            ->setNextUrl(route('coupons.edit', $coupon));
    }

    public function edit(Coupon $coupon): View
    {
        Assets::usingVueJS()
            ->addStyles('timepicker')
            ->addScripts(['timepicker', 'form-validation'])
            ->addScriptsDirectly('vendor/core/plugins/job-board/js/coupon.js');

        $jsValidator = JsValidator::formRequest(CouponRequest::class);

        return view('plugins/job-board::coupons.edit', compact('coupon', 'jsValidator'));
    }

    public function update(Coupon $coupon, CouponRequest $request): BaseHttpResponse
    {
        $coupon->update(
            array_merge(
                $request->validated(),
                $request->has('never_expired')
                    ? ['expires_date' => null]
                    : ['expires_date' => $request->date('expires_date')->setTimeFrom($request->input('expires_time'))]
            )
        );

        event(new UpdatedContentEvent(COUPON_MODULE_SCREEN_NAME, $request, $coupon));

        return $this
            ->httpResponse()
            ->withUpdatedSuccessMessage();
    }

    public function destroy(Coupon $coupon)
    {
        return DeleteResourceAction::make($coupon);
    }

    public function generateCouponCode(): BaseHttpResponse
    {
        do {
            $code = strtoupper(Str::random(12));
        } while (Coupon::query()->where('code', $code)->exists());

        return $this
            ->httpResponse()
            ->setData($code);
    }
}
