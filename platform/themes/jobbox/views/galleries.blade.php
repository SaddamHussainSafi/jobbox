@php
    Theme::set('pageTitle', 'Galleries');
@endphp

<section class="section page-intro pt-100 pb-100 bg-cover">
    {!! Theme::partial('breadcrumbs') !!}
</section>

<section class="section pt-50 pb-100">
    <div class="container">
        <div class="page-content">
            <article class="post post--single">
                <div class="post__content">
                    @if (isset($galleries) && !$galleries->isEmpty())
                        <div class="gallery-wrap">
                            @foreach ($galleries as $gallery)
                                <div class="gallery-item">
                                    <div class="img-wrap">
                                        <a href="{{ $gallery->url }}"><img src="{{ RvMedia::getImageUrl($gallery->image) }}" alt="{{ $gallery->name }}"></a>
                                    </div>
                                    <div class="gallery-detail">
                                        <div class="gallery-title"><a href="{{ $gallery->url }}">{{ $gallery->name }}</a></div>
                                        <div class="gallery-author">{{ __('By :user', ['user' => $gallery->user->name]) }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </article>
        </div>
    </div>
</section>
