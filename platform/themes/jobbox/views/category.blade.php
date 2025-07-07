<div>
    @php
        Theme::set('pageTitle', $category->name);
        Theme::set('pageDescription', Str::limit($category->description, 50));
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

