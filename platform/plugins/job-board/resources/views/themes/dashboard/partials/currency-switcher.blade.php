@php
    $currencies = get_all_currencies();
@endphp

@if($currencies->count() > 1)
    <div class="dropdown d-inline-block currency-switch">
        <a
            class="btn-currency-footer dropdown-toggle"
            data-bs-toggle="dropdown"
            type="button"
            href="#"
            aria-haspopup="true"
            aria-expanded="false"
        >
            {{ get_application_currency()->title }}
        </a>
        <div class="dropdown-menu dropdown-menu-end">
            @foreach ($currencies as $currency)
                <a
                    class="dropdown-item notify-item language"
                    href="{{ route('public.change-currency', $currency->title) }}"
                ><span>{{ $currency->title }}</span></a>
            @endforeach
        </div>
    </div>
@endif
