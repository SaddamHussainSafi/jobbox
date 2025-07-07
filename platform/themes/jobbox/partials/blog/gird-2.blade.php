@php
    $post = get_featured_posts(1)->first();
@endphp

@if($post)
    <div class="row">
        <div class="col-lg-5 col-md-12 col-sm-12">
            <a href="{{ $post->url }}">
                <img src="{{ RvMedia::getImageUrl($post->image, 'small', false, RvMedia::getDefaultImage()) }}" alt="{{ $post->name }}">
            </a>
        </div>
        <div class="col-lg-7 col-md-12 col-sm-12">
            <div class="pt-40 pb-30 pl-30 pr-30">
                @foreach( $post->categories as $category)
                    <a class="btn btn-tag" href="{{ $category->url }}">{{ $category->name }}</a>&nbsp;
                @endforeach
                <h2 class="mt-20 mb-20">
                    <a href="{{ $post->url }}">{{ $post->name }}</a>
                </h2>
                <p class="font-md mb-20">{{ $post->description }}</p>
                <div>
                    <a class="btn btn-arrow-right" href="{{ $post->url }}">{{ __('Read More') }}</a>
                </div>
            </div>
        </div>
    </div>
@endif
