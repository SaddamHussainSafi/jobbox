<?php

use Botble\Widget\AbstractWidget;

class BlogSearchWidget extends AbstractWidget
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Blog Search',
            'description' => __('Search blog posts'),
        ]);
    }
}
