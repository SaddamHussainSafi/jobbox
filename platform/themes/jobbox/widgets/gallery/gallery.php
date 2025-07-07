<?php

use Botble\Widget\AbstractWidget;

class GalleryWidget extends AbstractWidget
{
    public function __construct()
    {
        parent::__construct([
            'name' => __('Gallery'),
            'description' => __('Gallery widget.'),
            'title_gallery' => __('Title Gallery.'),
            'number_image' => __('Number of image.'),
        ]);
    }
}
