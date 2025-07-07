@if (is_plugin_active('blog'))
    <aside class="widget widget_search">
        <div class="search-form">
            <form role="search" method="GET" action="{{ route('public.search') }}">
                <input type="text" placeholder="{{ __('Search...') }}" value="{{ BaseHelper::stringify(request()->query('q')) }}" name="q">
                <button type="submit"><i class="fi-rr-search"></i></button>
            </form>
        </div>
    </aside>
@endif
