<div class="col-md-7 text-lg-end social-share">
    <h6 class="color-text-paragraph-2 d-inline-block d-baseline mr-10">{{ __('Share this') }}</h6>
    <a class="mr-5 d-inline-block d-middle" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($job->url) }}">
        <img alt="Facebook" src="{{ Theme::asset()->url('imgs/template/icons/share-fb.svg') }}">
    </a>
    <a class="mr-5 d-inline-block d-middle" href="https://twitter.com/intent/tweet?url={{ urlencode($job->url) }}&text={{ strip_tags($job->description) }}">
        <img alt="Twitter" src="{{ Theme::asset()->url('imgs/template/icons/share-tw.svg') }}">
    </a>
    <a class="mr-5 d-inline-block d-middle" href="http://www.reddit.com/submit?url={{ urlencode($job->url) }}">
        <img alt="Reddit" src="{{ Theme::asset()->url('imgs/template/icons/share-red.svg') }}">
    </a>
    <a class="d-inline-block d-middle" href="https://wa.me?text={{ urlencode($job->url) }}">
        <img alt="Whatsapp" src="{{ Theme::asset()->url('imgs/template/icons/share-whatsapp.svg') }}">
    </a>
</div>
