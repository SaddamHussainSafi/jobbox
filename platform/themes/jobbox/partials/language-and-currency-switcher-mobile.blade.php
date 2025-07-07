@php
    $currencies = get_all_currencies();
    $supportedLocales = Language::getSupportedLocales();
    if (empty($options)) {
        $options = [
            'before' => '',
            'lang_flag' => true,
            'lang_name' => true,
            'class' => '',
            'after' => '',
        ];
    }

    $displayLanguageSwitcher = $supportedLocales && count($supportedLocales) > 1;
    $displayCurrencySwitcher = $currencies->count() > 1;
@endphp

@if ($displayLanguageSwitcher || $displayCurrencySwitcher)
    <div class="mobile-menu-switcher">
        <ul class="mobile-menu font-heading">
            @if ($displayCurrencySwitcher)
                <li class="has-children"><span class="menu-expand"><i class="fi-rr-angle-small-down"></i></span>
                    <a>
                        {{ $currencyActive = get_application_currency()->title }}
                        <div class="arrow-down"></div>
                    </a>
                    <ul class="sub-menu" style="display: none;">
                        @foreach ($currencies as $currency)
                            @if ($currency->title != $currencyActive)
                                <li>
                                    <a href="{{ route('public.change-currency', $currency->title) }}">
                                        {{ $currency->title }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </li>
            @endif

            @if ($displayLanguageSwitcher)
                @php
                    $languageDisplay = setting('language_display', 'all');
                    $showRelated = setting('language_show_default_item_if_current_version_not_existed', true);
                @endphp
                <li class="has-children"><span class="menu-expand"><i class="fi-rr-angle-small-down"></i></span>
                    <a>
                        {!! language_flag(Language::getCurrentLocaleFlag(), Language::getCurrentLocaleName()) !!}
                        <span>&nbsp;{{ Language::getCurrentLocaleName() }}</span>
                        <div class="arrow-down"></div>
                    </a>
                    <ul class="sub-menu" style="display: none;">
                        @foreach ($supportedLocales as $localeCode => $properties)
                            @if ($localeCode != Language::getCurrentLocale())
                                <li>
                                    <a href="{{ $showRelated ? Language::getLocalizedURL($localeCode) : url($localeCode) }}">
                                        @if (Arr::get($options, 'lang_flag', true) && ($languageDisplay == 'all' || $languageDisplay == 'flag'))
                                            {!! language_flag($properties['lang_flag'], $properties['lang_name']) !!}
                                        @endif
                                        @if (Arr::get($options, 'lang_name', true) && ($languageDisplay == 'all' || $languageDisplay == 'name'))
                                            &nbsp;<span>&nbsp;{{ $properties['lang_name'] }}</span>
                                        @endif
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </li>
            @endif
        </ul>
    </div>
@endif
