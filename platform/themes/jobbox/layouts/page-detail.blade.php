{!! Theme::partial('header') !!}

{!! Theme::partial('breadcrumbs') !!}

@if (is_plugin_active('ads'))
    {!! apply_filters('ads_render', null, 'main_content_before', ['class' => 'my-2 text-center']) !!}
@endif

{!! Theme::content() !!}

@if (is_plugin_active('ads'))
    {!! apply_filters('ads_render', null, 'main_content_after', ['class' => 'my-2 text-center']) !!}
@endif

{!! Theme::partial('footer') !!}
