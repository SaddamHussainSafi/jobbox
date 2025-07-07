@php
    Theme::set('pageTitle', $post->name);

    $coverImage = '';

    if ($post->getMetaData('cover_image', true)) {
        $coverImage = $post->getMetaData('cover_image', true);
    } elseif (theme_option('background_blog_single')) {
        $coverImage = theme_option('background_blog_single');
    }
@endphp

<main class="main">
    @if (is_plugin_active('ads'))
        {!! apply_filters('ads_render', null, 'post_before', ['class' => 'my-2 text-center']) !!}
    @endif

    <section class="section-box">
        @if($coverImage)
            <img class="cover-image-post" src="{{ RvMedia::getImageUrl($coverImage, null, false, RvMedia::getDefaultImage()) }}" alt="{{ $post->title }}">
        @endif
    </section>
    <section class="section-box">
        <div class="archive-header pt-50 text-center">
            <div class="container">
                <div class="box-white">
                    <div class="max-width-single">
                        @foreach ($post->categories as $category)
                            <a class="btn btn-tag" href="{{ $category->url }}">{{ $category->name }}</a>&nbsp;
                        @endforeach
                        <h2 class="mb-30 mt-20 text-center">{{ $post->name }}</h2>
                        <div class="post-meta text-muted d-flex align-items-center mx-auto justify-content-center">
                            @if (! theme_option('hide_blog_post_author') && !empty($post->author))
                                <div class="author d-flex align-items-center mr-30">
                                    <img  src="{{ $post->author->avatar_url }}">
                                    <span>{{ $post->author->name }}</span>
                                </div>
                            @endif
                            <div class="date">
                                <span class="font-xs color-text-paragraph-2 mr-20 d-inline-block">
                                    <img class="img-middle mr-5" src="{{ Theme::asset()->url('imgs/page/blog/calendar.svg') }}">
                                    {{ $post->created_at->translatedFormat('M d, Y') }}
                                </span>
                                <span class="font-xs color-text-paragraph-2 d-inline-block">
                                    <img class="img-middle mr-5" src="{{ Theme::asset()->url('imgs/template/icons/time.svg') }}">
                                    {{ __(':time mins to read', ['time' => MetaBox::getMetaData($post, 'time_to_read', true) ?:  number_format(strlen(strip_tags($post->content)) / 300)]) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="post-loop-grid">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="single-body">
                        <div class="max-width-single">
                            <div class="font-lg mb-30">
                                <p>{{ $post->description }}</p>
                            </div>
                        </div>
                        <div class="max-width-single">
                            <div class="content-single">
                                <div class="ck-content">
                                    {!! BaseHelper::clean($post->content) !!}
                                </div>
                            </div>
                            <div class="single-apply-jobs mt-20">
                                <div class="row">
                                    <div class="col-lg-7">
                                        @foreach ($post->tags as $tag)
                                            <a class="btn btn-border-3 mr-10 hover-up" href="{{ $tag->url }}"># {{ $tag->name }}</a>
                                        @endforeach
                                    </div>
                                    <div class="col-md-5 text-lg-end social-share">
                                        <h6 class="color-text-paragraph-2 d-inline-block d-baseline mr-20 mt-10">{{ __('Share') }}</h6>
                                        <a class="mr-20 d-inline-block d-middle hover-up" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($post->url) }}">
                                            <img alt="{{ __('Facebook') }}" src="{{ Theme::asset()->url('imgs/page/blog/fb.svg') }}">
                                        </a>
                                        <a class="mr-20 d-inline-block d-middle hover-up" href="https://twitter.com/intent/tweet?url={{ urlencode($post->url) }}&text={{ strip_tags($post->description) }}">
                                            <img alt="{{ __('Twitter') }}" src="{{ Theme::asset()->url('imgs/page/blog/tw.svg') }}">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (is_plugin_active('ads'))
        {!! apply_filters('ads_render', null, 'post_after', ['class' => 'my-2 text-center']) !!}
    @endif
</main>
