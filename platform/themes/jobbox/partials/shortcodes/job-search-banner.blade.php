<section class="section-box bg-15 pt-50 pb-50 mt-80">
    <div class="container">
        <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 text-center mb-30">
                <img class="img-job-search mt-20" src="{{ RvMedia::getImageUrl($shortcode->background_image) }}" alt="{!! BaseHelper::clean($shortcode->title) !!}"></div>
            <div class="col-xl-5 col-lg-6 col-md-12 col-sm-12">
                <h2 class="mb-40">{!! BaseHelper::clean($shortcode->title) !!}</h2>
                @for($i = 1; $i <= 3; $i++)
                    @if ($shortcode->{'checkbox_title_' . $i})
                        <div class="box-checkbox mb-30">
                            <h6>{!! BaseHelper::clean($shortcode->{'checkbox_title_' . $i}) !!}</h6>
                            <p class="font-md color-text-paragraph-2">{!! BaseHelper::clean($shortcode->{'checkbox_description_' . $i}) !!}</p>
                        </div>
                    @endif
                @endfor
            </div>
        </div>
    </div>
</section>
