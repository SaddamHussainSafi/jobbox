{!! Theme::partial('header') !!}

{!! Theme::partial('breadcrumbs') !!}

<div class="container">
    <div class="mt-4">
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
