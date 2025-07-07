<section class="section-box mt-70">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mb-40">
                @if ($title = $shortcode->title)
                    <span class="font-md color-brand-2 mt-20 d-inline-block">{!! BaseHelper::clean($title) !!}</span>
                @endif

                @if ($subtitle = $shortcode->subtitle)
                    <h2 class="mt-5 mb-10">{!! BaseHelper::clean($subtitle) !!}</h2>
                @endif

                @if ($description = $shortcode->description)
                    <p class="font-md color-text-paragraph-2">{!! BaseHelper::clean($description) !!}</p>
                @endif

                {!! $form->renderForm() !!}
            </div>
            @if($shortcode->image)
                <div class="col-lg-4 text-center d-none d-lg-block">
                    <img src="{{ RvMedia::getImageUrl($shortcode->image) }}" alt="{{ setting('site_title') }}">
                </div>
            @endif
        </div>
    </div>
</section>
