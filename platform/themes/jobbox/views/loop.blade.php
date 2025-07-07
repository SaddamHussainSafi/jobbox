@if (theme_option('blog_page_template') !== 'off')
    <section class="section-box mt-50">
        <div class="container">
            @if ((theme_option('blog_page_template') === 'blog_gird_1'))
                {!! Theme::partial('blog.gird-1') !!}
            @else
                {!! Theme::partial('blog.gird-2') !!}
            @endif
        </div>
    </section>
@endif

<section class="section-box mt-50">
    @if (is_plugin_active('ads'))
        {!! apply_filters('ads_render', null, 'post_list_before', ['class' => 'my-2 text-center']) !!}
    @endif

    <div class="post-loop-grid">
        <div class="container">
            <div class="text-left">
                <h2 class="section-title mb-10 wow animate__animated animate__fadeInUp">{{ __('Latest Posts') }}</h2>
                <p class="font-lg color-text-paragraph-2 wow animate__animated animate__fadeInUp">{{ __("Don't miss the trending news") }}</p>
            </div>

            {!! Theme::partial('blog.posts', compact('posts')) !!}
        </div>
    </div>

    @if (is_plugin_active('ads'))
        {!! apply_filters('ads_render', null, 'post_list_after', ['class' => 'my-2 text-center']) !!}
    @endif
</section>
