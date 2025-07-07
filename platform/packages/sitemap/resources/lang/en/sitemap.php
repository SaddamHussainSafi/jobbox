<?php

return [
    'settings' => [
        'title' => 'Sitemap',
        'description' => 'Manage sitemap configuration',
        'enable_sitemap' => 'Enable sitemap?',
        'enable_sitemap_help' => 'When enabled, a sitemap.xml file will be generated to help search engines better index your site.',
        'enable_stores_sitemap' => 'Enable stores sitemap?',
        'enable_stores_sitemap_help' => 'Include store pages in the sitemap to improve their visibility in search engines.',
        'enable_blog_tags_sitemap' => 'Enable blog tags sitemap?',
        'enable_blog_tags_sitemap_help' => 'Include blog tag pages in the sitemap to help search engines discover your blog content.',
        'sitemap_items_per_page' => 'Sitemap items per page',
        'sitemap_items_per_page_help' => 'The number of items to include in each sitemap page. Larger values may improve sitemap generation performance but could cause issues with very large sites. Default: 1000',
    ],
];
