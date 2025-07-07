<section class="section-box overflow-visible mt-50 mb-50">
    <div class="container">
        <div class="row">
            @for($i = 1; $i <= 4; $i++)
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                @if($shortcode->{'counter_title_' . $i})
                <div class="text-center">
                    <h3 class="color-brand-2">
                        <span class="count">{!! BaseHelper::clean($shortcode->{'counter_number_' . $i}) !!}</span>
                    </h3>
                    <h5>{!! BaseHelper::clean($shortcode->{'counter_title_' . $i}) !!}</h5>
                    <p class="font-sm color-text-paragraph mt-10">{!! BaseHelper::clean($shortcode->{'counter_description_' . $i}) !!}</p>
                </div>
                @endif
            </div>
            @endfor
        </div>
    </div>
</section>
