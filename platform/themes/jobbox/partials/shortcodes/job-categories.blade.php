<form id="form-page-categories">
    <input type="hidden" name="page">
</form>
<section class="section-box mt-80" @if($shortcode->background_image) style="background-image: url({{ RvMedia::getImageUrl($shortcode->background_image) }});" @endif>
    <div class="section-box wow animate__animated animate__fadeIn">
        <div class="container">
            <div class="text-center mb-3">
                <h2 class="section-title mb-10 wow animate__animated animate__fadeInUp">
                    {!! BaseHelper::clean($shortcode->title) !!}
                </h2>
                <p class="font-lg color-text-paragraph-2 wow animate__animated animate__fadeInUp">
                    {!! BaseHelper::clean($shortcode->subtitle) !!}
                </p>
            </div>

            <div class="row job-categories">
                @foreach($categories as $category)
                    <div class="col-sm-3">
                        <a href="{{ $category->url }}">
                            <div class="item-logo">
                                <div class="image-left">
                                    @if ($iconImage = $category->getMetaData('icon_image', true))
                                        <img src="{{ RvMedia::getImageUrl($iconImage) }}" alt="{{ $category->name }}">
                                    @elseif ($icon = $category->getMetaData('icon', true))
                                        <i class="{{ $icon }}"></i>
                                    @endif
                                        </div>
                                        <div class="text-info-right">
                                            <h4>{!! BaseHelper::clean($category->name) !!}</h4>
                                            <p class="font-xs">
                                                @if($category->jobs_count > 1)
                                                    {!! BaseHelper::clean(__(':number <span> Jobs Available </span>', ['number' => $category->jobs_count ])) !!}
                                                @elseif($category->jobs_count == 1)
                                                    {!! BaseHelper::clean(__(':number <span> Job Available </span>', ['number' => $category->jobs_count ])) !!}
                                                @else
                                                    {!! BaseHelper::clean(__('No <span> Job Available </span>')) !!}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                    {!! $categories->withQueryString()->links(Theme::getThemeNamespace('partials.pagination')) !!}
            </div>
        </div>
</section>
