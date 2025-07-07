@if (theme_option('preloader_enabled', 'yes') == 'yes')
    <div id="preloader-active">
        <div class="preloader d-flex align-items-center justify-content-center">
            <div class="preloader-inner position-relative">
                <div class="typing-indicator">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
    </div>
@endif

@if (is_plugin_active('job-board'))
    @include(Theme::getThemeNamespace('partials.apply-modal'))
@endif
<header class="header @if (theme_option('enabled_sticky_header', 'yes') == 'yes') sticky-bar @endif">
    <div class="container">
        <div class="main-header">
            <div class="header-left">
                <div class="header-logo">
                    <a class="d-flex" href="{{ route('public.index') }}">
                        <img alt="{{ theme_option('site_title') }}" src="{{ setting('theme-jobbox-logo') ? RvMedia::getImageUrl(setting('theme-jobbox-logo')) : url(config('core.base.general.logo')) }}">
                    </a>
                </div>
            </div>
            <div class="header-nav">
                <nav class="nav-main-menu">
                    {!!
                        Menu::renderMenuLocation('main-menu', [
                            'options' => ['class' => 'main-menu'],
                            'view'    => 'main-menu',
                        ])
                    !!}
                </nav>
                <div class="burger-icon burger-icon-white">
                    <span class="burger-icon-top"></span>
                    <span class="burger-icon-mid"></span>
                    <span class="burger-icon-bottom"></span>
                </div>
            </div>
            <div class="header-right">
                @if (is_plugin_active('job-board'))
                    @auth('account')
                        <ul class="header-menu list-inline d-flex align-items-center mb-0 user-header-dropdown">
                            {!! apply_filters('theme-header-right-nav', null) !!}
                            @if (auth('account')->check() && $account = auth('account')->user())
                                <li class="list-inline-item dropdown">
                                    <a href="#" class="d-inline-flex header-item" id="userdropdown" data-bs-toggle="dropdown"
                                       aria-expanded="false">
                                        <img src="{{ $account->avatar_thumb_url }}" alt="{{ $account->name }}" width="35" height="35" class="rounded-circle me-1 mt-1 mr-2">
                                        <span class="text-left fw-medium icon-down" title="{{ __('Hi, :name', ['name' => $name = Str::limit($account->name, 15)]) }}">{{ __('Hi, :name', ['name' => $name]) }} </span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end user-dropdown-menu" aria-labelledby="userdropdown">
                                        @if ($account->isEmployer())
                                            <li><a class="dropdown-item" href="{{ route('public.account.dashboard') }}">{{ __('Employer Dashboard') }}</a></li>
                                        @else
                                            <li><a class="dropdown-item" href="{{ route('public.account.jobs.saved') }}">{{ __('Saved Jobs') }}</a></li>
                                            <li><a class="dropdown-item" href="{{ route('public.account.jobs.applied-jobs') }}">{{ __('Applied Jobs') }}</a></li>
                                        @endif
                                        <li><a class="dropdown-item" href="{{ route('public.account.settings') }}">{{ __('Account Settings') }}</a></li>
                                        <li>
                                            <a class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" href="#">{{ __('Logout') }}</a>
                                            <form id="logout-form" action="{{ route('public.account.logout') }}" method="POST" style="display: none;">
                                                @csrf
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                        </ul>
                    @else
                        <div class="block-signin">
                            <a class="text-link-bd-btom hover-up" href="{{ route('public.account.register') }}"><x-core::icon name="ti ti-user-plus" class="me-1" />{{ __('Register') }}</a>
                            <a class="btn btn-default btn-shadow ml-30 hover-up" href="{{ route('public.account.login') }}"><x-core::icon name="ti ti-user-shield" class="me-1" />{{ __('Sign In') }}</a>
                        </div>
                    @endauth
                @endif
            </div>
        </div>
    </div>
</header>
<div class="mobile-header-active mobile-header-wrapper-style perfect-scrollbar">
    <div class="offcanvas-header justify-content-end">
        <button type="button" class="btn-close burger-close burger-icon" aria-label="Close"></button>
    </div>
    <div class="mobile-header-wrapper-inner">
        <div class="mobile-header-content-area">
            <div class="perfect-scroll">
                <div class="mobile-search mobile-header-border mb-30">
                    <form action="#">
                        <input type="text" placeholder="{{ __('Search...') }}">
                        <i class="fi-rr-search"></i>
                    </form>
                </div>
                <div class="mobile-menu-wrap mobile-header-border">
                    <nav>
                        {!!
                            Menu::renderMenuLocation('main-menu', [
                                'options' => ['class' => 'mobile-menu font-heading'],
                                'view'    => 'main-menu',
                            ])
                        !!}
                        @if (is_plugin_active('language'))
                            {!! Theme::partial('language-and-currency-switcher-mobile') !!}
                        @endif
                    </nav>
                </div>
                @if (is_plugin_active('job-board'))
                    @auth('account')
                        <div class="mobile-account">
                            <h6 class="mb-10">{{ __('Your Account') }}</h6>
                            <ul class="mobile-menu font-heading">
                                @if (auth('account')->user()->isEmployer())
                                    <li><a href="{{ route('public.account.dashboard') }}">{{ __('Employer Dashboard') }}</a></li>
                                @else
                                    <li><a href="{{ route('public.account.jobs.saved') }}">{{ __('Saved Jobs') }}</a></li>
                                    <li><a href="{{ route('public.account.jobs.applied-jobs') }}">{{ __('Applied Jobs') }}</a></li>
                                @endif
                                <li><a href="{{ route('public.account.settings') }}">{{ __('Account Settings') }}</a></li>
                                <li><a href="{{ route('public.account.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit()">{{ __('Sign Out') }}</a></li>
                            </ul>
                        </div>
                        <form id="logout-form" action="{{ route('public.account.logout') }}" method="post">
                            @csrf
                        </form>
                    @else
                        <div class="mobile-account">
                            <ul class="mobile-menu font-heading">
                                <li><a href="{{ route('public.account.login') }}"><x-core::icon name="ti ti-user-plus" class="me-1" />{{ __('Sign In') }}</a></li>
                                <li><a href="{{ route('public.account.register') }}"><x-core::icon name="ti ti-user-shield" class="me-1" />{{ __('Sign Up') }}</a></li>
                            </ul>
                        </div>
                    @endauth
                @endif
                <div class="site-copyright">{!! BaseHelper::clean(theme_option('copyright')) !!}</div>
            </div>
        </div>
    </div>
</div>
