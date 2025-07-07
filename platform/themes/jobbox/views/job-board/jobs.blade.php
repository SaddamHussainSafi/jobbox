@php
    Theme::asset()->container('footer')->usePath()->add('no-ui-slider', 'js/noUISlider.js');

    if (theme_option('show_map_on_jobs_page', 'yes') === 'yes') {
        Theme::asset()->usePath()->add('leaflet-css', 'plugins/leaflet/leaflet.css');
        Theme::asset()->container('footer')->usePath()->add('leaflet-js', 'plugins/leaflet/leaflet.js');
        Theme::asset()->container('footer')->usePath()->add('leaflet-markercluster-js', 'plugins/leaflet/leaflet.markercluster-src.js');
    }
@endphp

<section class="section-box">
    <div class="banner-hero hero-2 hero-3 pb-70">
        <div class="banner-inner">
            <div class="block-banner">
                <h1 class="text-42 color-white wow animate__ animate__fadeInUp animated" style="visibility: visible; animation-name: fadeInUp;">
                    {{ SeoHelper::getTitle()  }}
                </h1>
            </div>
        </div>
    </div>
</section>

<section class="section-box mt-30">
    @if (is_plugin_active('ads'))
        {!! apply_filters('ads_render', null, 'job_list_before', ['class' => 'my-2 text-center']) !!}
    @endif

    <div class="container">
        <div class="row flex-row-reverse justify-content-center row-filter">
            <div class="col-lg-9 col-md-12 col-sm-12 row col-12 float-right jobs-listing">
                @include(Theme::getThemeNamespace('views.job-board.partials.job-items'), ['jobs' => $jobs, 'perPages' => $perPages ?? JobBoardHelper::getPerPageParams()])
            </div>

            @include(Theme::getThemeNamespace('views.job-board.partials.filters'))
        </div>
    </div>

    @if (is_plugin_active('ads'))
        {!! apply_filters('ads_render', null, 'job_list_after', ['class' => 'my-2 text-center']) !!}
    @endif
</section>

@if(theme_option('show_map_on_jobs_page', 'yes') === 'yes')
    <script id="traffic-popup-map-template" type="text/x-jquery-tmpl">
        @include(Theme::getThemeNamespace('views.job-board.partials.map'))
    </script>
@endif
