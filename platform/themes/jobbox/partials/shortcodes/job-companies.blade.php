@php
    Theme::asset()->container('footer')->usePath()->add('no-ui-slider', 'js/noUISlider.js');
    Theme::asset()->container('footer')->usePath()->add('company-filter', 'js/company.js');
@endphp
<div class="companies-list">
    @if (is_plugin_active('ads'))
        {!! apply_filters('ads_render', null, 'company_list_before', ['class' => 'my-2 text-center']) !!}
    @endif

    <section class="section-box-2">
        <div class="container">
            <div class="banner-hero banner-company">
                <div class="block-banner text-center">
                    <h3 class="wow animate__animated animate__fadeInUp">{!! BaseHelper::clean($shortcode->title) !!}</h3>
                    <div class="font-sm color-text-paragraph-2 mt-10 wow animate__animated animate__fadeInUp" data-wow-delay=".1s">{!! BaseHelper::clean($shortcode->subtitle) !!}</div>
                    <div class="box-list-character">
                        <ul>
                            @foreach(range('A', 'Z') as $word)
                                <li>
                                    <a class="filter-by-word @if(BaseHelper::stringify(request()->query('keyword')) == $word) active @endif" data-keyword="{{ $word }}" href="#">{{ $word }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section-box mt-30">
        <div class="container">
            <div id="loading" class="loading" style="display:none">
                <div class="preloader d-flex align-items-center justify-content-center">
                    <div class="preloader-inner position-relative">
                        <div class="text-center"><img src="{{ Theme::asset()->url('imgs/template/loading.gif') }}" alt="loading.gif"></div>
                    </div>
                </div>
            </div>
            <div class="row flex-row-reverse row-filter justify-content-center">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12 company-listing">
                    @include(Theme::getThemeNamespace('views.job-board.partials.companies'), ['companies' => $companies])
                </div>
            </div>
            {!! Form::open(['url' => route('public.ajax.companies'), 'method' => 'GET', 'id' => 'company-filter-form']) !!}
            <input type="hidden" name="per_page" value="{{ BaseHelper::stringify(request()->query('per_page')) }}">
            <input type="hidden" name="page" value="{{ BaseHelper::stringify(request()->query('page')) }}">
            <input type="hidden" name="layout" value="{{ BaseHelper::stringify(request()->query('layout')) }}">
            <input type="hidden" name="keyword" value="{{ BaseHelper::stringify(request()->query('keyword')) }}">
            <input type="hidden" name="sort_by" value="{{ BaseHelper::stringify(request()->query('sort_by')) }}">
            {!! Form::close() !!}
        </div>
    </section>

    @if (is_plugin_active('ads'))
        {!! apply_filters('ads_render', null, 'company_list_after', ['class' => 'my-2 text-center']) !!}
    @endif
</div>

