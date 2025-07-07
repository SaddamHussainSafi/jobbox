<div class="footer-col-1 col-md-3 col-sm-12">
    <a href="{{ route('public.index') }}" aria-label="{{ theme_option('site_title') }}">
        <img
            alt="{{ setting('site_title') }}"
            src="{{ RvMedia::getImageUrl($config['logo'] ?: theme_option('theme-jobbox-logo')) }}"
        >
    </a>
    <div class="mt-20 mb-20 font-xs color-text-paragraph-2">
        {!! BaseHelper::clean($config['introduction']) !!}
    </div>

    @if($socialLinks = json_decode(theme_option('social_links')))
        <div class="footer-social">
            @foreach($socialLinks as $social)
                @php($social = collect($social)->pluck('value', 'key'))
                <a class="icon-socials" title="{{ $social->get('social-name') }}" href="{{ $social->get('social-url') }}" target="_blank">
                    <img src="{{ RvMedia::getImageUrl($social->get('social-icon')) }}" alt="{{ $social->get('social-name') }}">
                </a>
            @endforeach
        </div>
    @endif
</div>
