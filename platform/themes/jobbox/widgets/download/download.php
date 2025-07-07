<?php

use Botble\Widget\AbstractWidget;

class DownloadWidget extends AbstractWidget
{
    public function __construct()
    {
        parent::__construct([
            'name' => __('Download'),
            'description' => __('Show the download buttons for Android and iOS.'),
            'label' => __('Download App'),
            'app_store_url' => null,
            'app_store_image' => null,
            'android_app_url' => null,
            'google_play_image' => null,
        ]);
    }
}
