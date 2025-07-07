<?php

use Botble\Base\Facades\BaseHelper;
use Botble\Shortcode\View\View;
use Botble\Theme\Theme;

return [

    /*
    |--------------------------------------------------------------------------
    | Inherit from another theme
    |--------------------------------------------------------------------------
    |
    | Set up inherit from another if the file is not exists,
    | this is work with "layouts", "partials" and "views"
    |
    | [Notice] assets cannot inherit.
    |
    */

    'inherit' => null, //default

    /*
    |--------------------------------------------------------------------------
    | Listener from events
    |--------------------------------------------------------------------------
    |
    | You can hook a theme when event fired on activities
    | this is cool feature to set up a title, meta, default styles and scripts.
    |
    | [Notice] these events can be overridden by package config.
    |
    */

    'events' => [

        // Before event inherit from package config and the theme that call before,
        // you can use this event to set meta, breadcrumb template or anything
        // you want inheriting.
        'before' => function ($theme): void {
            // You can remove this line anytime.
        },

        // Listen on event before render a theme,
        // this event should call to assign some assets,
        // breadcrumb template.
        'beforeRenderTheme' => function (Theme $theme): void {
            $version = get_cms_version();

            if (BaseHelper::isRtlEnabled()) {
                $theme->asset()->usePath()->add('bootstrap', 'plugins/bootstrap/bootstrap.rtl.min.css');
            } else {
                $theme->asset()->usePath()->add('bootstrap', 'plugins/bootstrap/bootstrap.min.css');
            }

            // You may use this event to set up your assets.
            $theme->asset()->usePath()->add('style', 'css/style.css', [], [], $version);

            if (BaseHelper::isRtlEnabled()) {
                $theme->asset()->usePath()->add('style-rtl', 'css/style-rtl.css', [], [], $version);
            }

            $scripts = [
                'modernizr' => 'plugins/modernizr-3.6.0.min.js',
                'jquery' => 'plugins/jquery-3.6.3.min.js',
                'jquery-migrate' => 'plugins/jquery-migrate-3.3.0.min.js',
                'bootstrap' => 'plugins/bootstrap/bootstrap.bundle.min.js',
                'waypoints' => 'plugins//waypoints.js',
                'magnific_popup' => 'plugins//magnific-popup.js',
                'perfect_scrollbar' => 'plugins/perfect-scrollbar.min.js',
                'select2' => 'plugins/select2.min.js',
                'isotope' => 'plugins/isotope.js',
                'scrollup' => 'plugins/scrollup.js',
                'swiper_bundle' => 'plugins/swiper-bundle.min.js',
                'counterup' => 'plugins/counterup.js',
                'main' => 'js/main.js',
                'script' => 'js/script.js',
                'backend' => 'js/backend.js',
            ];

            $hasVersions = ['main', 'script', 'app', 'backend'];

            if (theme_option('enabled_animation_when_loading_page', 'yes') === 'yes') {
                $theme->asset()->usePath()->add('animate-css', 'plugins/animate.min.css', [], [], $version);

                $theme->asset()
                    ->container('footer')
                    ->usePath()
                    ->add('wow', 'plugins/wow.js');
            }

            foreach ($scripts as $name => $path) {
                $theme->asset()
                    ->container('footer')
                    ->usePath()
                    ->add($name, $path, [], [], in_array($name, $hasVersions) ? $version : null);
            }

            if (function_exists('shortcode')) {
                $theme->composer([
                    'page',
                    'post',
                    'job-board.candidate',
                    'job-board.company',
                    'job-board.job',
                    'job-board.job-category',
                    'job-board.job-tag',
                ], function (View $view): void {
                    $view->withShortcodes();
                });
            }
        },

        // Listen on event before render a layout,
        // this should call to assign style, script for a layout.
        'beforeRenderLayout' => [

            'default' => function ($theme): void {
                // $theme->asset()->usePath()->add('ipad', 'css/layouts/ipad.css');
            },
        ],
    ],
];
