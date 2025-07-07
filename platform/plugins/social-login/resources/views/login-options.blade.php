<div class="login-options">
    <div class="login-options-title">
        <p>{{ __('Login with social networks') }}</p>
    </div>

    @if(setting('social_login_style', 'default') === 'basic')
        <ul class="social-login-basic">
            @foreach (SocialService::getProviderKeys() as $item)
                @continue(! SocialService::getProviderEnabled($item))

                @if ($item === 'google' && setting('social_login_google_use_google_button', false) && false)
                    @include('plugins/social-login::google-sign-in-button')
                    @continue
                @endif

                <li>
                    <a href="{{ route('auth.social', array_merge([$item], $params)) }}" class="social-login {{ $item }}-login">
                        @php
                            $item = $item === 'linkedin-openid' ? 'linkedin' : $item;
                        @endphp

                        <img src="{{ asset('vendor/core/plugins/social-login/images/icons/logo-' . $item . '.svg') }}" alt="{{ Str::ucfirst($item) }}" />
                        <span>{{ trans('plugins/social-login::social-login.sign_in_with', ['provider' => trans('plugins/social-login::social-login.socials.' . $item)]) }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    @else
        <ul @class(['social-icons', 'social-login-lg' => setting('social_login_style', 'default') === 'default'])>
            @foreach (SocialService::getProviderKeys() as $item)
                @continue(! SocialService::getProviderEnabled($item))

                @if ($item === 'google' && setting('social_login_google_use_google_button', false) && false)
                    @include('plugins/social-login::google-sign-in-button')
                    @continue
                @endif

                {!! apply_filters(
                    'social_login_' . $item . '_render',
                    view('plugins/social-login::social-login-item', ['social' => $item, 'url' => route('auth.social', isset($params) ? array_merge([$item], $params) : $item)])->render(),
                    $item
                ) !!}
            @endforeach
        </ul>
    @endif
</div>
