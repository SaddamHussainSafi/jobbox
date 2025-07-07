@php
    Theme::set('pageTitle', $gallery->name);
    Theme::set('pageDescription', $gallery->description);
@endphp

<section class="section page-intro pt-100 pb-100 bg-cover">
    {!! Theme::partial('breadcrumbs') !!}
</section>

<section class="section pb-100">
<div class="container">
        <div class="page-content">
            <article class="post post--single">
                <div class="post__content">
   <div class="ck-content">
            {!! BaseHelper::clean($gallery->description) !!}
        </div><br />
                    <div id="list-photo">
                        @foreach (gallery_meta_data($gallery) as $image)
                            @if ($image)
                                <div class="item" data-src="{{ RvMedia::getImageUrl(Arr::get($image, 'img')) }}" data-sub-html="{{ BaseHelper::clean(Arr::get($image, 'description')) }}">
                                    <div class="photo-item">
                                        <div class="thumb">
                                            <a href="{{ BaseHelper::clean(Arr::get($image, 'description')) }}">
                                                <img src="{{ RvMedia::getImageUrl(Arr::get($image, 'img')) }}" alt="{{ BaseHelper::clean(Arr::get($image, 'description')) }}">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <br>
                    {!! apply_filters(BASE_FILTER_PUBLIC_COMMENT_AREA, null) !!}
                </div>
            </article>
        </div>
    </div>
</section>
