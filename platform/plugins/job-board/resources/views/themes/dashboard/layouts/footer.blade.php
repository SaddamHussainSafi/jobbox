<footer>
    @php
        $currencies = get_all_currencies();
    @endphp

    @if ($currencies->count() > 1)
        <p class="d-inline-block mb-0">{{ __('Currencies') }}:
            @foreach ($currencies as $currency)
                <a
                    href="{{ route('public.change-currency', $currency->title) }}"
                    @if (get_application_currency_id() == $currency->id) class="active" @endif
                ><span>{{ $currency->title }}</span></a>
                @if (!$loop->last)
                    -
                @endif
            @endforeach
        </p>
    @endif
</footer>

<script src="{{ asset('vendor/core/plugins/job-board/js/app.js') }}"></script>
