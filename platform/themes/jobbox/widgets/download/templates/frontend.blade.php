<div class="footer-col-6 col-md-3 col-sm-12">
    <div class="h6 mb-20">{{ $config['label'] }}</div>
    <p class="color-text-paragraph-2 font-xs">
        {!! BaseHelper::clean(theme_option('app_advertisement')) !!}
    </p>
    <div class="mt-15">
        @if ($url = $config['app_store_url'])
            @if ($image = $config['app_store_image'])
                <a class="mr-5" href="{{ $url }}">
                    <img src="{{ RvMedia::getImageUrl($image) }}" alt="{{ __('App Store') }}">
                </a>
            @endif
        @endif
        @if ($url = $config['android_app_url'])
            @if ($image = $config['google_play_image'])
                <a class="mr-5" href="{{ $url }}">
                    <img src="{{ RvMedia::getImageUrl($image) }}" alt="{{ __('Google Play') }}">
                </a>
            @endif
        @endif
    </div>
</div>
