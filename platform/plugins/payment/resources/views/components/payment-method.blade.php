@props([
    'name',
    'label' => null,
    'description' => null,
    'paymentName' => null,
    'supportedCurrencies' => [],
])

@php
    $isSelected = PaymentMethods::getSelectingMethod() === $name;
    $id = sprintf('payment-%s', $name);
@endphp

<li class="list-group-item payment-method-item">
    <input
        class="magic-radio js_payment_method"
        id="{{ $id }}"
        name="payment_method"
        type="radio"
        value="{{ $name }}"
        @checked($isSelected)
    >
    <label for="{{ $id }}" class="form-label fw-medium">
        {{ $paymentLabel = ($label ?: get_payment_setting('name', $name) ?: setting('payment_' . $name . '_name') ?: $paymentName) }}
    </label>

    <div @class(['payment_collapse_wrap collapse mt-1', 'show' => $isSelected])>
        <p class="text-muted">{!! BaseHelper::clean($description ?: get_payment_setting('description', $name) ?: setting('payment_' . $name . '_description')) !!}</p>
        @php
            $feeValue = get_payment_setting('fee', $name, 0);
            $feeType = get_payment_setting('fee_type', $name, \Botble\Payment\Enums\PaymentFeeTypeEnum::FIXED);
            $orderAmount = apply_filters('payment_order_total_amount', 0);
            $fee = \Botble\Payment\Supports\PaymentFeeHelper::calculateFee($name, $orderAmount);
        @endphp
        @if ($feeValue > 0)
            <p class="text-warning">
                @if ($feeType === \Botble\Payment\Enums\PaymentFeeTypeEnum::PERCENTAGE)
                    {{ __('plugins/payment::payment.payment_fee') }}: {{ format_price($fee) }} ({{ $feeValue }}%)
                @else
                    {{ __('plugins/payment::payment.payment_fee') }}: {{ format_price($fee) }}
                @endif
                <input type="hidden" name="payment_fee" value="{{ $fee }}" class="payment-fee-input" data-method="{{ $name }}">
            </p>
        @endif

        {{ $slot }}

        {!! apply_filters('payment_method_display_body', null, $name, $paymentLabel) !!}

        @if (
            ! empty($supportedCurrencies)
            && ! in_array(get_application_currency()->title, $supportedCurrencies)
            && ! get_application_currency()->replicate()->newQuery()->whereIn('title', $supportedCurrencies)->exists()
        )
            @php
                $currencies = get_all_currencies()->filter(fn ($item) => in_array($item->title, $supportedCurrencies));
            @endphp

            <div class="alert alert-warning mt-3">
                {{ __(":name doesn't support :currency. List of currencies supported by :name: :currencies.", ['name' => $paymentName, 'currency' => get_application_currency()->title, 'currencies' => implode(', ', $supportedCurrencies)]) }}

                {{ $currencyNotSupportedMessage ?? '' }}

                @if ($currencies->isNotEmpty())
                    <div>
                        {{ __('Please switch currency to any supported currency') }}:&nbsp;&nbsp;
                        @foreach ($currencies as $currency)
                            <a
                                href="{{ route('public.change-currency', $currency->title) }}"
                                @class(['active' => get_application_currency_id() === $currency->getKey()])
                            >
                                {{ $currency->title }}
                            </a>
                            @if (!$loop->last)
                                &nbsp; | &nbsp;
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    </div>

    @if ($logo = get_payment_setting('logo', $name))
        <div class="payment-method-logo">
            {{ RvMedia::image($logo) }}
        </div>
    @endif
</li>
