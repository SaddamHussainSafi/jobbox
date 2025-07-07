@php
    $blogSidebar = dynamic_sidebar('blog_sidebar');
@endphp

<div class="row mt-30">
    <div @class(['col-lg-8' => $blogSidebar, 'col-lg-12' => ! $blogSidebar])>
        <div class="row">
            @foreach ($posts as $post)
                <div @class(['mb-30', 'col-lg-6' => $blogSidebar, 'col-lg-4' => ! $blogSidebar])>
                    {!! Theme::partial('blog.box-post', ['post' => $post]) !!}
                </div>
            @endforeach
        </div>
        {!! $posts->withQueryString()->links(Theme::getThemeNamespace('partials.pagination')) !!}
    </div>
    @if ($blogSidebar)
        <div class="col-lg-4 col-md-12 col-sm-12 col-12 pl-40 pl-lg-15 mt-lg-30">
            @if (is_plugin_active('ads'))
                {!! apply_filters('ads_render', null, 'blog_sidebar_before', ['class' => 'my-2 text-center']) !!}
            @endif

            {!! dynamic_sidebar('blog_sidebar') !!}

            @if (is_plugin_active('ads'))
                {!! apply_filters('ads_render', null, 'blog_sidebar_after', ['class' => 'my-2 text-center']) !!}
            @endif
        </div>
    @endif
</div>
