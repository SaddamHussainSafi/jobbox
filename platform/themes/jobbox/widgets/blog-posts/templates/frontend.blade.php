@if (is_plugin_active('blog'))
    @php
    $numberDisplay =(int) $config['number_display'];
        switch ($config['type']) {
            case 'recent':
                $posts = get_recent_posts($numberDisplay);
                break;
            default:
                $posts = get_popular_posts($numberDisplay);
                break;
        }
    @endphp
    @if ($posts->count())
        <div class="mt-4 pt-2">
            <ul class="widget-popular-post list-unstyled my-4">
                <div class="sidebar-shadow sidebar-news-small">
                    <h5 class="sidebar-title">{{ $config['name'] ?: __('Blog Post') }}</h5>
                    <div class="post-list-small">
                        @foreach ($posts as $post)
                            <a href="{{ $post->url }}">
                                <div class="post-list-small-item d-flex align-items-center">
                                    <figure class="thumb mr-15"><img
                                            src="{{ RvMedia::getImageUrl($post->image, 'thumb', false, RvMedia::getDefaultImage()) }}"
                                            alt="{{ $post->name }}"></figure>
                                    <div class="content">
                                        <h5>{{ $post->name }}</h5>
                                        <div class="post-meta text-muted d-flex align-items-center mb-15">
                                            @php
                                                $author = $post->author;
                                                $isDisplayAuthor = ! theme_option('hide_blog_post_author') && $author->getKey();
                                            @endphp

                                            @if ($isDisplayAuthor)
                                                <div class="author d-flex align-items-center mr-20">
                                                    <img alt="{{ $author->name }}" src="{{ $author->avatar_url }}"><span>{{ $author->name }}</span>
                                                </div>
                                            @endif
                                            <div class="date">
                                                <span>{{ $post->created_at->translatedFormat('M d') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </ul>
        </div>
    @endif
@endif
