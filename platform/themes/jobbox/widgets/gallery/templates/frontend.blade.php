@if (is_plugin_active('gallery'))
    <div class="sidebar-shadow sidebar-news-small">
        <h5 class="sidebar-title">{{ $config['title_gallery'] }}</h5>
        <div class="post-list-small">
            <ul class="gallery-3">
                @foreach(get_galleries((int) $config['number_image']) as $gallery)
                    <li><a href="{{ $gallery->url }}"><img src="{{ Rvmedia::getImageUrl($gallery->image, 'thumb', false, RvMedia::getDefaultImage()) }}"></a></li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
