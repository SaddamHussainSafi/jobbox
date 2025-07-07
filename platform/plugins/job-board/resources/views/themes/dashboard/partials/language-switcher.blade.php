@php
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

    $languageDisplay = setting('language_display', 'all');
    $showRelated = setting('language_show_default_item_if_current_version_not_existed', true);
@endphp

@if (setting('language_switcher_display', 'dropdown') == 'dropdown')
    <li @class([
        'dropdown nav-item',
        'me-5' => !is_in_admin(),
        'me-2' => is_in_admin(),
    ])>
        @if ($supportedLocales && count($supportedLocales) > 1)
            {!! Arr::get($options, 'before') !!}
            <a
                class="dropdown-toggle"
                id="dropdownLanguage"
                data-bs-toggle="dropdown"
                href="#"
                aria-expanded="false"
            >
                <span class="text-language">
                    @if (Arr::get($options, 'lang_flag', true) && ($languageDisplay == 'all' || $languageDisplay == 'flag'))
                        {!! language_flag(Language::getCurrentLocaleFlag(), Language::getCurrentLocaleName()) !!}
                    @endif
                    @if (Arr::get($options, 'lang_name', true) && ($languageDisplay == 'all' || $languageDisplay == 'name'))
                        {{ Language::getCurrentLocaleName() }}
                    @endif
                    <span class="caret"></span>
                </span>
            </a>
            <div
                class="dropdown-menu dropdown-menu-end float-end"
                aria-labelledby="dropdownLanguage"
            >
                @foreach ($supportedLocales as $localeCode => $properties)
                    @if ($localeCode != Language::getCurrentLocale())
                        <a
                            class="dropdown-item"
                            href="{{ $showRelated ? Language::getLocalizedURL($localeCode) : url($localeCode) }}"
                        >
                            @if (Arr::get($options, 'lang_flag', true) && ($languageDisplay == 'all' || $languageDisplay == 'flag'))
                                {!! language_flag($properties['lang_flag'], $properties['lang_name']) !!}
                            @endif
                            @if (Arr::get($options, 'lang_name', true) && ($languageDisplay == 'all' || $languageDisplay == 'name'))
                                <span>{{ $properties['lang_name'] }}</span>
                            @endif
                        </a>
                    @endif
                @endforeach
            </div>
            {!! Arr::get($options, 'after') !!}
        @endif
        @if (!is_in_admin())
            &nbsp;&nbsp;
            @include(JobBoardHelper::viewPath('dashboard.partials.currency-switcher'))
        @endif
    </li>
@else
    @if ($supportedLocales && count($supportedLocales) > 1)
        @foreach ($supportedLocales as $localeCode => $properties)
            @if ($localeCode != Language::getCurrentLocale())
                <li class="me-3">
                    <a href="{{ $showRelated ? Language::getLocalizedURL($localeCode) : url($localeCode) }}">
                        @if (Arr::get($options, 'lang_flag', true) && ($languageDisplay == 'all' || $languageDisplay == 'flag'))
                            {!! language_flag($properties['lang_flag'], $properties['lang_name']) !!}
                        @endif
                        @if (Arr::get($options, 'lang_name', true) && ($languageDisplay == 'all' || $languageDisplay == 'name'))
                            <span>{{ $properties['lang_name'] }}</span>
                        @endif
                    </a>
                </li>
            @endif
        @endforeach
    @endif
    &nbsp;&nbsp;
    @include(JobBoardHelper::viewPath('dashboard.partials.currency-switcher'))
@endif

