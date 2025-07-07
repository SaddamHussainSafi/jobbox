<div>
    @php
        Theme::set('pageTitle', __('Search'));
        Theme::set('pageDescription', '');
        $blogSidebar = dynamic_sidebar('blog_sidebar');
    @endphp

    {!! Theme::partial('breadcrumbs') !!}
</div>
<section class="section-box mt-50">
    <div class="post-loop-grid">
        <div class="container">
            <div class="row mt-30">
                <div @class(['col-lg-8' => $blogSidebar, 'col-lg-12' => !$blogSidebar])>
                    <div class="row">
                        @forelse ($posts as $post)
                            <div @class(['mb-30', 'col-lg-6' => $blogSidebar, 'col-lg-4' => ! $blogSidebar])>
                                {!! Theme::partial('blog.box-post', ['post' => $post]) !!}
                            </div>
                        @empty
                            <div class="job-empty">
                                <div class="text-center mt-2">
                                    <i class="fi fi-rr-sad text-3xl"></i>

                                    <h3 class="mt-2">{{ __('No Posts') }}</h3>

                                    <div class="mt-2 text-muted">
                                        {{ __('There are no posts found with your queries.') }}
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                    @if ($posts->isNotEmpty())
                        {!! $posts->withQueryString()->links(Theme::getThemeNamespace('partials.pagination')) !!}
                    @endif
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
        </div>
    </div>
</section>
