<?php

use Botble\JobBoard\Facades\JobBoardHelper;
use Carbon\Carbon;

app()->booted(function (): void {
    theme_option()
        ->setSection([
            'title' => __('Styles'),
            'id' => 'opt-text-subsection-style',
            'subsection' => true,
            'icon' => 'ti ti-brush',
        ])
        ->setField([
            'id' => 'preloader_enabled',
            'section_id' => 'opt-text-subsection-general',
            'type' => 'customSelect',
            'label' => __('Enable Preloader?'),
            'attributes' => [
                'name' => 'preloader_enabled',
                'list' => [
                    'yes' => __('Yes'),
                    'no' => __('No'),
                ],
                'value' => 'yes',
            ],
        ])
        ->setField([
            'id' => 'scroll_to_top',
            'section_id' => 'opt-text-subsection-general',
            'type' => 'customSelect',
            'label' => __('Enable Scroll To Top?'),
            'attributes' => [
                'name' => 'scroll_to_top',
                'list' => [
                    'yes' => __('Yes'),
                    'no' => __('No'),
                ],
                'value' => 'yes',
            ],
        ])
        ->setField([
            'id' => 'copyright',
            'section_id' => 'opt-text-subsection-general',
            'type' => 'text',
            'label' => __('Copyright'),
            'attributes' => [
                'name' => 'copyright',
                'value' => __('Copyright © :year Your Company. All right reserved', ['year' => Carbon::now()->year]),
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => __('Change copyright'),
                    'data-counter' => 250,
                ],
            ],
            'helper' => __('Copyright on footer of site'),
        ])
        ->setField([
            'id' => 'introduction',
            'section_id' => 'opt-text-subsection-general',
            'type' => 'text',
            'label' => __('Introduction'),
            'attributes' => [
                'name' => 'introduction',
                'value' => __('JobBox is the heart of the design community and the best resource to discover and connect with designers and jobs worldwide.'),
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => __('Change introduction'),
                    'data-counter' => 250,
                ],
            ],
            'helper' => __('Introduction on footer of site'),
        ])
        ->setField([
            'id' => 'app_advertisement',
            'section_id' => 'opt-text-subsection-general',
            'type' => 'text',
            'label' => __('App advertisement'),
            'attributes' => [
                'name' => 'app_advertisement',
                'value' => __('Download our Apps and get extra 15% Discount on your first Order&mldr;!'),
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => __('Change app advertisement'),
                    'data-counter' => 250,
                ],
            ],
            'helper' => __('App advertisement on footer of site'),
        ])
        ->setField([
            'id' => 'blog_page_template',
            'section_id' => 'opt-text-subsection-blog',
            'type' => 'customSelect',
            'label' => __('Blog featured posts template (in blog list)'),
            'attributes' => [
                'name' => 'blog_page_template',
                'list' => ['blog_gird_1' => __('Blog Grid 1'), 'blog_gird_2' => __('Blog Grid 2'), 'off' => __('Turn off')],
                'value' => 'blog_gird_2',
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ])
        ->setField([
            'id' => 'hide_blog_post_author',
            'section_id' => 'opt-text-subsection-blog',
            'type' => 'onOff',
            'label' => __('Hide blog post author?'),
            'attributes' => [
                'name' => 'hide_blog_post_author',
                'value' => false,
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ])
        ->setField([
            'id' => 'background_breadcrumb',
            'section_id' => 'opt-text-subsection-blog',
            'type' => 'mediaImage',
            'label' => __('Background Breadcrumb'),
            'attributes' => [
                'name' => 'background_breadcrumb',
                'value' => '',
            ],
        ])
        ->setField([
            'id' => 'background_blog_single',
            'section_id' => 'opt-text-subsection-blog',
            'type' => 'mediaImage',
            'label' => __('Background Blog Single'),
            'attributes' => [
                'name' => 'background_blog_single',
                'value' => '',
            ],
        ])
        ->setField([
            'id' => 'job_board_max_salary_filter',
            'section_id' => 'opt-text-subsection-job-board',
            'type' => 'number',
            'label' => __('Max salary filter'),
            'attributes' => [
                'name' => 'job_board_max_salary_filter',
                'value' => is_plugin_active('job-board') ? JobBoardHelper::getJobMaxPrice() : [],
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ])
        ->when(is_plugin_active('location'), function (): void {
            theme_option()
                ->setField([
                    'id' => 'hide_job_location_filter',
                    'section_id' => 'opt-text-subsection-job-board',
                    'type' => 'onOff',
                    'label' => __('Hide job location filter?'),
                    'attributes' => [
                        'name' => 'hide_job_location_filter',
                        'value' => false,
                        'options' => [
                            'class' => 'form-control',
                        ],
                    ],
                ]);
        })
        ->setField([
            'id' => 'auth_background_image_1',
            'section_id' => 'opt-text-subsection-job-board',
            'type' => 'mediaImage',
            'label' => __('Authentication background image 1'),
            'attributes' => [
                'name' => 'auth_background_image_1',
                'value' => null,
            ],
        ])
        ->setField([
            'id' => 'auth_background_image_2',
            'section_id' => 'opt-text-subsection-job-board',
            'type' => 'mediaImage',
            'label' => __('Authentication background image 2'),
            'attributes' => [
                'name' => 'auth_background_image_2',
                'value' => null,
            ],
        ])
        ->setField([
            'id' => '404_page_image',
            'section_id' => 'opt-text-subsection-page',
            'type' => 'mediaImage',
            'label' => __('404 page image'),
            'attributes' => [
                'name' => '404_page_image',
                'value' => '',
            ],
        ])
        ->setField([
            'id' => 'primary_font',
            'section_id' => 'opt-text-subsection-style',
            'type' => 'googleFonts',
            'label' => __('Primary font'),
            'attributes' => [
                'name' => 'primary_font',
                'value' => 'Plus Jakarta Sans',
            ],
        ])
        ->setField([
            'id' => 'primary_color',
            'section_id' => 'opt-text-subsection-style',
            'type' => 'customColor',
            'label' => __('Primary color'),
            'attributes' => [
                'name' => 'primary_color',
                'value' => '#3C65F5',
            ],
        ])
        ->setField([
            'id' => 'primary_color_hover',
            'section_id' => 'opt-text-subsection-style',
            'type' => 'customColor',
            'label' => __('Primary color when hovering'),
            'attributes' => [
                'name' => 'primary_color_hover',
                'value' => '#b4c0e0',
            ],
        ])
        ->setField([
            'id' => 'secondary_color',
            'section_id' => 'opt-text-subsection-style',
            'type' => 'customColor',
            'label' => __('Secondary color'),
            'attributes' => [
                'name' => 'secondary_color',
                'value' => '#05264E',
            ],
        ])
        ->setField([
            'id' => 'border_color_2',
            'section_id' => 'opt-text-subsection-style',
            'type' => 'customColor',
            'label' => __('Border color'),
            'attributes' => [
                'name' => 'border_color_2',
                'value' => '#E0E6F7',
            ],
        ])
        ->setField([
            'id' => 'enabled_sticky_header',
            'section_id' => 'opt-text-subsection-style',
            'type' => 'customSelect',
            'label' => __('Enable sticky header?'),
            'attributes' => [
                'name' => 'enabled_sticky_header',
                'list' => [
                    'yes' => trans('core/base::base.yes'),
                    'no' => trans('core/base::base.no'),
                ],
                'value' => 'yes',
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ])
        ->setField([
            'id' => 'enabled_animation_when_loading_page',
            'section_id' => 'opt-text-subsection-style',
            'type' => 'customSelect',
            'label' => __('Enable animation when loading page?'),
            'attributes' => [
                'name' => 'enabled_animation_when_loading_page',
                'list' => [
                    'yes' => trans('core/base::base.yes'),
                    'no' => trans('core/base::base.no'),
                ],
                'value' => 'yes',
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ])
        ->setField([
            'id' => 'background_cover_candidate_default',
            'section_id' => 'opt-text-subsection-job-board',
            'type' => 'mediaImage',
            'label' => __('Background cover candidate default'),
            'attributes' => [
                'name' => 'background_cover_candidate_default',
                'value' => '',
            ],
        ])
        ->setSection([
            'title' => __('Social Links'),
            'desc' => __('Social Links at the footer.'),
            'id' => 'opt-text-subsection-social-links',
            'subsection' => true,
            'icon' => 'ti ti-share',
            'fields' => [
                [
                    'id' => 'social_links',
                    'type' => 'repeater',
                    'label' => __('Social Links'),
                    'attributes' => [
                        'name' => 'social_links',
                        'value' => null,
                        'fields' => [
                            [
                                'type' => 'text',
                                'label' => __('Name'),
                                'attributes' => [
                                    'name' => 'social-name',
                                    'value' => null,
                                    'options' => [
                                        'class' => 'form-control',
                                    ],
                                ],
                            ],
                            [
                                'type' => 'mediaImage',
                                'label' => __('Icon'),
                                'attributes' => [
                                    'name' => 'social-icon',
                                    'value' => null,
                                    'options' => [
                                        'class' => 'form-control',
                                    ],
                                ],
                            ],
                            [
                                'type' => 'text',
                                'label' => __('URL'),
                                'attributes' => [
                                    'name' => 'social-url',
                                    'value' => null,
                                    'options' => [
                                        'class' => 'form-control',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ])
        ->setField([
            'id' => 'job_location_filter_by',
            'section_id' => 'opt-text-subsection-job-board',
            'type' => 'customSelect',
            'label' => __('Job location filter by'),
            'attributes' => [
                'name' => 'job_location_filter_by',
                'list' => [
                    'state' => __('State'),
                    'city' => __('City'),
                ],
                'value' => 'state',
            ],
        ])
        ->setField([
            'id' => 'limit_results_on_job_location_filter',
            'section_id' => 'opt-text-subsection-job-board',
            'type' => 'number',
            'label' => __('Limit results on job location filter?'),
            'helper' => __('Enter the number of results to be displayed on the job location filter. Enter 0 to display all results.'),
            'attributes' => [
                'name' => 'limit_results_on_job_location_filter',
                'value' => 10,
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ]);
});
