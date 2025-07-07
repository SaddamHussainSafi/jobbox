<div
    class="js-cookie-consent cookie-consent cookie-consent-{{ theme_option('cookie_consent_style', 'full-width') }}"
    style="background-color: {{ theme_option('cookie_consent_background_color', '#000') }}; color: {{ theme_option('cookie_consent_text_color', '#fff') }};"
    dir="{{ BaseHelper::siteLanguageDirection() }}"
>
    <div
        class="cookie-consent-body"
        style="max-width: {{ theme_option('cookie_consent_max_width', 1170) }}px;"
    >
        <div class="cookie-consent__inner">
            <div class="cookie-consent__message">
                {!! BaseHelper::clean(theme_option('cookie_consent_message', trans('plugins/cookie-consent::cookie-consent.message'))) !!}
                @if (($learnMoreUrl = theme_option('cookie_consent_learn_more_url')) && ($learnMoreText = theme_option('cookie_consent_learn_more_text')))
                    <a href="{{ Str::startsWith($learnMoreUrl, ['http://', 'https://']) ? $learnMoreUrl : BaseHelper::getHomepageUrl() . '/' . $learnMoreUrl }}">{{ $learnMoreText }}</a>
                @endif
            </div>

            <div class="cookie-consent__actions">
                @if (theme_option('cookie_consent_show_reject_button', 'no') == 'yes')
                    <button
                        class="js-cookie-consent-reject cookie-consent__reject"
                        style="background-color: {{ theme_option('cookie_consent_text_color', '#fff') }}; color: {{ theme_option('cookie_consent_background_color', '#000') }}; border: 1px solid {{ theme_option('cookie_consent_text_color', '#fff') }};"
                    >
                        {{ trans('plugins/cookie-consent::cookie-consent.reject_text') }}
                    </button>
                @endif
                @if (! empty($cookieConsentConfig['cookie_categories']) && theme_option('cookie_consent_show_customize_button', 'no') == 'yes')
                    <button
                        class="js-cookie-consent-customize cookie-consent__customize"
                        style="background-color: {{ theme_option('cookie_consent_text_color', '#fff') }}; color: {{ theme_option('cookie_consent_background_color', '#000') }}; border: 1px solid {{ theme_option('cookie_consent_text_color', '#fff') }};"
                    >
                        {{ trans('plugins/cookie-consent::cookie-consent.customize_text') }}
                    </button>
                @endif
                <button
                    class="js-cookie-consent-agree cookie-consent__agree"
                    style="background-color: {{ theme_option('cookie_consent_background_color', '#000') }}; color: {{ theme_option('cookie_consent_text_color', '#fff') }}; border: 1px solid {{ theme_option('cookie_consent_text_color', '#fff') }};"
                >
                    {{ theme_option('cookie_consent_button_text', trans('plugins/cookie-consent::cookie-consent.button_text')) }}
                </button>
            </div>
        </div>

        @if (! empty($cookieConsentConfig['cookie_categories']))
            <div class="cookie-consent__categories">
                @foreach ($cookieConsentConfig['cookie_categories'] as $key => $category)
                    <div class="cookie-category">
                        <label class="cookie-category__label">
                            <input type="checkbox"
                                name="cookie_category[]"
                                value="{{ $key }}"
                                class="js-cookie-category"
                                @if ($category['required']) checked disabled @endif
                            >
                            <span class="cookie-category__name">{{ trans('plugins/cookie-consent::cookie-consent.cookie_categories.' . $key . '.name') }}</span>
                        </label>
                        <p class="cookie-category__description">{{ trans('plugins/cookie-consent::cookie-consent.cookie_categories.' . $key . '.description') }}</p>
                    </div>
                @endforeach

                <div class="cookie-consent__save">
                    <button
                        class="js-cookie-consent-save cookie-consent__save-button"
                        style="background-color: {{ theme_option('cookie_consent_background_color', '#000') }}; color: {{ theme_option('cookie_consent_text_color', '#fff') }}; border: 1px solid {{ theme_option('cookie_consent_text_color', '#fff') }};"
                    >
                        {{ trans('plugins/cookie-consent::cookie-consent.save_text') }}
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>

<div data-site-cookie-name="{{ $cookieConsentConfig['cookie_name'] ?? 'cookie_for_consent' }}"></div>
<div data-site-cookie-lifetime="{{ $cookieConsentConfig['cookie_lifetime'] ?? 36000 }}"></div>
<div data-site-cookie-domain="{{ config('session.domain') ?? request()->getHost() }}"></div>
<div data-site-session-secure="{{ config('session.secure') ? ';secure' : null }}"></div>

<script>
    window.addEventListener('load', function () {
        if (typeof gtag !== 'undefined') {
            gtag('consent', 'default', {
                'ad_storage': 'denied',
                'analytics_storage': 'denied'
            });

            document.addEventListener('click', function(event) {
                if (event.target.classList.contains('js-cookie-consent-agree')) {
                    const categories = document.querySelectorAll('.js-cookie-category:checked');
                    const consents = {
                        'ad_storage': 'denied',
                        'analytics_storage': 'denied'
                    };

                    categories.forEach(function(category) {
                        if (category.value === 'marketing') {
                            consents.ad_storage = 'granted';
                        }
                        if (category.value === 'analytics') {
                            consents.analytics_storage = 'granted';
                        }
                    });

                    gtag('consent', 'update', consents);
                }
            });
        }
    });
</script>
