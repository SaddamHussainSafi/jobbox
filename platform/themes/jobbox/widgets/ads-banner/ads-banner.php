<?php

use Botble\Widget\AbstractWidget;

class AdsBannerWidget extends AbstractWidget
{
    public function __construct()
    {
        parent::__construct([
            'name' => __('Ads banner'),
            'description' => __('Ads banner widget.'),
            'banner_ads' => null,
            'url' => null,
        ]);
    }
}
