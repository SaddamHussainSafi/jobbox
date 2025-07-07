@php
    Theme::asset()->container('footer')->usePath()->add('no-ui-slider', 'js/noUISlider.js');

    if (theme_option('show_map_on_jobs_page', 'yes') === 'yes') {
        Theme::asset()->usePath()->add('leaflet-css', 'plugins/leaflet/leaflet.css');
        Theme::asset()->container('footer')->usePath()->add('leaflet-js', 'plugins/leaflet/leaflet.js');
        Theme::asset()->container('footer')->usePath()->add('leaflet-markercluster-js', 'plugins/leaflet/leaflet.markercluster-src.js');
    }
@endphp
<section class="section-box mt-30">
    @if (is_plugin_active('ads'))
        {!! apply_filters('ads_render', null, 'job_list_before', ['class' => 'my-2 text-center']) !!}
    @endif

    <div class="container">
        <div class="row row-filter">
            @include(Theme::getThemeNamespace('views.job-board.partials.filters'))

            <div class="col-12 col-lg-9 jobs-listing">
                @include(Theme::getThemeNamespace('views.job-board.partials.job-items'), ['jobs' => $jobs, 'perPages' => $perPages])
            </div>
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

