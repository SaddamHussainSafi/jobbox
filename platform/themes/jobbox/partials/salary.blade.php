@php
    $currency = $job->currency->getKey() ? $job->currency : get_application_currency();
@endphp

@if ($job->hide_salary)
    <span class="text-muted">{{ __('Attractive') }}</span>
@elseif ($job->salary_from || $job->salary_to)
    @if(! JobBoardHelper::isSalaryHiddenForGuests())
        @if ($job->salary_from && $job->salary_to)
            <span class="card-text-price" title="{{ format_price($job->salary_from, $currency) }} - {{ format_price($job->salary_to, $currency) }}">
            {{ format_price($job->salary_from, $currency) }} - {{ format_price($job->salary_to, $currency) }}
        </span>
        @elseif ($job->salary_from)
            <span class="card-text-price" title="{{ __('From :price', ['price' => format_price($job->salary_from, $currency)]) }}">
            {{ __('From :price', ['price' => format_price($job->salary_from, $currency)]) }}
        </span>
        @elseif ($job->salary_to)
            <span class="card-text-price" title="{{ __('Upto :price', ['price' => format_price($job->salary_to, $currency)]) }}">
            {{ __('Upto :price', ['price' => format_price($job->salary_to, $currency)]) }}
        </span>
        @endif
        <span class="text-muted">/{{ $job->salary_range->label() }}</span>
    @else
        <a class="job-hidden-job-for-guest-text" href="{{ route('public.account.login') }}">
            <x-core::icon name="ti ti-coin" />
            {{ __('Sign in to view salary') }}
        </a>
    @endif
@else
    <span class="text-muted">{{ __('Attractive') }}</span>
@endif
