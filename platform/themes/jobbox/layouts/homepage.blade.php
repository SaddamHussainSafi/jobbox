{!! Theme::partial('header') !!}

<div class="main-content">
    <div class="page-content" id="app">
        @if (is_plugin_active('ads'))
            {!! apply_filters('ads_render', null, 'main_content_before', ['class' => 'my-2 text-center']) !!}
        @endif

        {!! Theme::content() !!}

        @if (is_plugin_active('ads'))
            {!! apply_filters('ads_render', null, 'main_content_after', ['class' => 'my-2 text-center']) !!}
        @endif
    </div>
</div>

{!! Theme::partial('footer') !!}
