<section class="section-box mt-70 mb-40">
    <div class="container">
        <div class="text-start">
            <h2 class="section-title mb-10 wow animate__animated animate__fadeInUp">
                {!! BaseHelper::clean($shortcode->title) !!}
            </h2>
            <p class="font-lg color-text-paragraph-2 wow animate__animated animate__fadeInUp">
                {!! BaseHelper::clean($shortcode->description) !!}
            </p>
        </div>
        <div class="mt-70">
            <div class="row">
                @for($i = 1; $i <= 3; $i++)
                    @php
                        $label = $shortcode->{'step_label_' . $i};
                        $help = $shortcode->{'step_help_' . $i}
                    @endphp
                    @if($label && $help)
                        <div class="col-lg-4">
                            <div class="box-step step-{{ $i }}">
                                <h1 class="number-element">{{ $i }}</h1>
                                <h4 class="mb-20">{!! BaseHelper::clean($label) !!}</h4>
                                <p class="font-lg color-text-paragraph-2">{!! BaseHelper::clean($help) !!}</p>
                            </div>
                        </div>
                    @endif
                @endfor
            </div>
        </div>
        @if($shortcode->button_label && $shortcode->button_url)
            <div class="mt-50 text-center">
                <a href="{{ $shortcode->button_url }}" class="btn btn-default">{{ $shortcode->button_label }}</a>
            </div>
        @endif
    </div>
</section>
