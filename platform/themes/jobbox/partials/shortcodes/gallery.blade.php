<section class="section-box">
    <div class="container">
        <div class="d-flex flex-wrap flex-lg-nowrap">
            <div class="block-banner-1">
                @if($url = $shortcode->image_1)
                    <div class="block-image-1">
                        <img src="{{ RvMedia::getImageUrl($url) }}" alt="{{ $shortcode->image_1 }}">
                    </div>
                @endif
                @if($url = $shortcode->image_2)
                    <div class="block-image-2">
                        <img src="{{ RvMedia::getImageUrl($url) }}" alt="{{ $shortcode->image_1 }}">
                    </div>
                @endif
            </div>
            <div class="block-banner-2">
                @if($url = $shortcode->image_3)
                    <div class="block-image-3">
                        <img src="{{ RvMedia::getImageUrl($url) }}" alt="{{ $shortcode->image_1 }}">
                    </div>
                @endif
            </div>
            <div class="block-banner-3">
                @if($url = $shortcode->image_4)
                    <div class="block-image-4">
                        <img src="{{ RvMedia::getImageUrl($url) }}" alt="{{ $shortcode->image_1 }}">
                    </div>
                @endif
                @if($url = $shortcode->image_5)
                    <div class="block-image-5">
                        <img src="{{ RvMedia::getImageUrl($url) }}" alt="{{ $shortcode->image_1 }}">
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
