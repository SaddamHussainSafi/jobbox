<div
    class="order-detail-box mt-3"
    data-refresh-url="{{ route('public.account.coupon.refresh', $package->getKey()) }}"
>
    <x-core::card>
        <x-core::card.header>
            <x-core::card.title>{{ __('Your order') }}</x-core::card.title>
        </x-core::card.header>
        <x-core::card.body>
            <dl>
                <div class="d-flex justify-content-between">
                    <dt>{{ $package->name }}</dt>
                    <dd>{{ format_price($package->price) }}</dd>
                </div>
            </dl>
            @if (session()->has('applied_coupon_code'))
                <dl>
                    <div class="d-flex justify-content-between">
                        <dt>{{ trans('plugins/job-board::coupon.coupon_code') }}</dt>
                        <dd>{{ session()->get('applied_coupon_code') }}</dd>
                    </div>
                </dl>

                @if (session()->get('coupon_discount_amount') > 0)
                    <dl>
                        <div class="d-flex justify-content-between">
                            <dt>{{ trans('plugins/job-board::coupon.discount_amount') }}</dt>
                            <dd class="text-success">{{ format_price(session()->get('coupon_discount_amount')) }}</dd>
                        </div>
                    </dl>
                @endif
            @endif
            <dl>
                <div class="d-flex justify-content-between">
                    <dt>{{ trans('plugins/job-board::coupon.total') }}</dt>
                    <dd class="fs-3 fw-bold">{{ format_price($totalAmount) }}</dd>
                </div>
            </dl>
        </x-core::card.body>
    </x-core::card>

    <div class="text-end mt-2 mb-3">
        <a class="toggle-coupon-form" role="button">
            {{ trans('plugins/job-board::coupon.toggle_coupon_form_text') }}
        </a>
    </div>

    @if (session('applied_coupon_code'))
        <x-core::alert>
            <div class="d-flex justify-content-between align-items-center">
                {!! BaseHelper::clean(__('Coupon code: :code', ['code' => '<strong class="ms-1">' . session('applied_coupon_code') . '</strong>'])) !!}

                <a
                    class="remove-coupon-code text-danger"
                    data-url="{{ route('public.account.coupon.remove') }}"
                    role="button"
                    data-bs-toggle="tooltip"
                    title="{{ __('Remove') }}"
                >
                    <x-core::icon name="ti ti-x" />
                </a>
            </div>
        </x-core::alert>
    @else
        <x-core::card
            class="coupon-form my-3"
            @style(['display: none' => !session()->has('applied_coupon_code')])
        >
            <x-core::card.body>
                <div class="row g-1">
                    <div class="col">
                        <x-core::form.text-input
                            :label="trans('plugins/job-board::coupon.coupon_code')"
                            name="coupon_code"
                            :value="old('coupon_code')"
                            :placeholder="trans('plugins/job-board::coupon.coupon_code_placeholder')"
                        >
                            <x-slot:append>
                                <x-core::button
                                    class="apply-coupon-code"
                                    data-url="{{ route('public.account.coupon.apply') }}"
                                >
                                    {{ trans('plugins/job-board::coupon.apply_coupon_code') }}
                                </x-core::button>
                            </x-slot:append>
                        </x-core::form.text-input>
                    </div>
                </div>
            </x-core::card.body>
        </x-core::card>
    @endif
</div>
