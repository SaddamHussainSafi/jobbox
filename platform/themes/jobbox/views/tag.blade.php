<div>
    @php
        Theme::set('pageTitle', $tag->name);
        Theme::set('pageDescription', Str::limit($tag->description, 50));
    @endphp
    {!! Theme::partial('breadcrumbs') !!}
</div>

<section class="section-box mt-50">
    <div class="post-loop-grid">
        <div class="container">
            {!! Theme::partial('blog.posts', compact('posts')) !!}
        </div>
    </div>
</section>
