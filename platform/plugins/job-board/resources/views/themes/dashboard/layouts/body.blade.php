<header class="header--mobile">
    <div class="header__left">
        <button class="navbar-toggler">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
    <div class="header__center">
        <a class="ps-logo" href="{{ route('public.account.dashboard') }}">
            @if ($logo = theme_option('logo', theme_option('logo')))
                <img
                    src="{{ RvMedia::getImageUrl($logo) }}"
                    alt="{{ theme_option('site_title') }}"
                >
            @endif
        </a>
    </div>
    <div class="header__right">
        <a
            href="{{ route('public.account.logout') }}"
            title="{{ trans('plugins/job-board::dashboard.header_logout_link') }}"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
        >
            <x-core::icon name="ti ti-logout" />
        </a>

        <form id="logout-form" style="display: none;" action="{{ route('public.account.logout') }}" method="POST">
            @csrf
        </form>
    </div>
</header>
<aside class="ps-drawer--mobile">
    <div class="ps-drawer__header py-3">
        <h4 class="fs-3 mb-0">{{ __('Menu') }}</h4>
        <button class="ps-drawer__close">
            <x-core::icon name="ti ti-x" />
        </button>
    </div>
    <div class="ps-drawer__content">
        @include('plugins/job-board::themes.dashboard.layouts.menu-top')

        <div class="my-4 border-bottom"></div>

        @include('plugins/job-board::themes.dashboard.layouts.menu')
    </div>
</aside>

<div class="ps-site-overlay"></div>

<main class="ps-main">
    <div class="ps-main__sidebar">
        <div class="ps-sidebar">
            @include('plugins/job-board::themes.dashboard.layouts.menu-top')

            <div class="ps-sidebar__content">
                <div class="ps-sidebar__center">
                    @include('plugins/job-board::themes.dashboard.layouts.menu')
                </div>
                <div class="ps-sidebar__footer">
                    <div class="ps-copyright">
                        @php $logo = theme_option('logo', theme_option('logo')); @endphp
                        @if ($logo)
                            <a href="{{ route('public.index') }}" title="{{ $siteTitle = theme_option('site_title') }}">
                                <img
                                    src="{{ RvMedia::getImageUrl($logo) }}"
                                    alt="{{ $siteTitle }}"
                                    height="40"
                                >
                            </a>
                        @endif

                        <p>{!! BaseHelper::clean(str_replace('%Y', date('Y'), theme_option('copyright'))) !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ps-main__wrapper">
        <header class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fs-1">{{ PageTitle::getTitle(false) }}</h3>

            <div class="d-flex align-items-center gap-4">
                <a href="{{ route('public.index') }}" target="_blank" class="text-uppercase">
                    {{ __('Go to homepage') }}
                    <x-core::icon name="ti ti-arrow-right" />
                </a>
            </div>
        </header>

        <div id="app">
            @if (JobBoardHelper::isEnabledCreditsSystem())
                <x-core::alert>
                    {{ trans('plugins/job-board::package.add_credit_warning') }}
                    <a href="{{ route('public.account.packages') }}">
                        {{ __('Buy credits') }}
                        <span class="mr-2 badge badge-danger">{{ auth('account')->user()->credits }}</span>
                    </a>
                </x-core::alert>
            @endif

            @yield('content')
        </div>
    </div>
</main>
