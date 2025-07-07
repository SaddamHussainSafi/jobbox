<section class="section-box mt-50">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-12 mb-15 mb-lg-0">
                <div class="box-radius-8 bg-urgent hover-up">
                    @if($url = $shortcode->image_of_first_content)
                        <div class="image">
                            <figure>
                                <img src="{{ RvMedia::getImageUrl($url) }}" alt="{{ $shortcode->first_image_alt_text }}">
                            </figure>
                        </div>
                    @endif
                    <div class="text-info">
                        <h3>{!! BaseHelper::clean($shortcode->first_title) !!}</h3>
                        <p class="font-sm color-text-paragraph-2">{!! BaseHelper::clean($shortcode->first_description) !!}</p>
                        @if($shortcode->load_more_link_first_content && $shortcode->load_more_first_content_text )
                            <div class="mt-15">
                                <a class="btn btn-arrow-right" href="{{ $shortcode->load_more_link_first_content }}">
                                    {!! BaseHelper::clean($shortcode->load_more_first_content_text) !!}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="box-radius-8 bg-9 hover-up">
                    @if($url = $shortcode->image_of_second_content)
                        <div class="image">
                            <figure>
                                <img src="{{ RvMedia::getImageUrl($url) }}" alt="{!! BaseHelper::clean($shortcode->second_image_alt_text) !!}">
                            </figure>
                        </div>
                    @endif
                    <div class="text-info">
                        <h3>{!! BaseHelper::clean($shortcode->second_title) !!}</h3>
                        <p class="font-sm color-text-paragraph-2">
                            {!! BaseHelper::clean($shortcode->second_description ) !!}
                        </p>
                        @if($shortcode->load_more_link_second_content && $shortcode->load_more_second_content_text)
                            <div class="mt-15">
                                <a class="btn btn-arrow-right" href="{{ $shortcode->load_more_link_second_content }}">
                                    {!! BaseHelper::clean($shortcode->load_more_second_content_text) !!}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
