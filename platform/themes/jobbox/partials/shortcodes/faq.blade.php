<section class="section-box mt-90 mb-50">
    <div class="container ">
        <h2 class="text-center mb-15 wow animate__ animate__fadeInUp animated" style="visibility: visible; animation-name: fadeInUp;">
            {!! BaseHelper::clean($shortcode->title) !!}
        </h2>
        <div class="font-lg color-text-paragraph-2 text-center wow animate__ animate__fadeInUp animated" style="visibility: visible; animation-name: fadeInUp;">
            {!! BaseHelper::clean($shortcode->subtitle) !!}
        </div>
        @if ($faqCategories->count() > 0)
            <div class="text-center">
                <div class="list-tabs mt-40">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="mr-5">
                            @foreach ($faqCategories as $category)
                                <a @class(['active' => $loop->first]) id="nav-tab-faq-category-{{ $category->id }}" href="#tab-faq-category-{{ $category->id }}" data-bs-toggle="tab" role="tab" aria-controls="tab-faq-category-{{ $category->id }}" @if(! $loop->first) @else aria-selected="false" @endif aria-selected="true">{{ $category->name }}</a>
                            @endforeach
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="mt-30">
                    <div class="tab-content" id="faqs">
                        @foreach ($faqCategories as $category)
                            <div @class(['tab-pane fade', 'active show' => $loop->first]) id="tab-faq-category-{{ $category->id }}" role="tabpanel" aria-labelledby="tab-faq-category-{{ $category->id }}">
                                <div class="row">
                                    @php($faqs = $category->faqs)
                                    @foreach ($faqs as $faq)
                                        <div class="col-lg-6 col-md-12 col-sm-12">
                                            <div class="card-grid-border hover-up wow animate__ animate__fadeIn animated" style="visibility: visible; animation-name: fadeIn;">
                                                <h4 class="mb-20">{!! BaseHelper::clean($faq->question) !!}</h4>
                                                <p class="font-sm mb-20 color-text-paragraph">{!! BaseHelper::clean($faq->answer) !!}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div class="row mt-50">
                @foreach ($faqs as $faq)
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="card-grid-border hover-up wow animate__ animate__fadeIn animated" style="visibility: visible; animation-name: fadeIn;">
                            <h4 class="mb-20">{!! BaseHelper::clean($faq->question) !!}</h4>
                            <p class="font-sm mb-20 color-text-paragraph">{!! BaseHelper::clean($faq->answer) !!}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
