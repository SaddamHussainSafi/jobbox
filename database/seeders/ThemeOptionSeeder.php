<?php

namespace Database\Seeders;

use Botble\Base\Models\BaseModel;
use Botble\Base\Supports\BaseSeeder;
use Botble\Page\Models\Page;
use Botble\Setting\Models\Setting;
use Botble\Theme\Facades\Theme;
use Carbon\Carbon;

class ThemeOptionSeeder extends BaseSeeder
{
    public function run(): void
    {
        $this->uploadFiles('general');
        $this->uploadFiles('authentication');
        $this->uploadFiles('pages');
        $this->uploadFiles('socials');

        $theme = Theme::getThemeName();

        Setting::query()->where('key', 'LIKE', 'theme-' . $theme . '-%')
            ->orWhereIn('key', [
                'show_admin_bar',
                'theme',
                'admin_logo',
                'admin_favicon',
            ])
            ->delete();

        Setting::query()->insertOrIgnore([
            [
                'key' => 'show_admin_bar',
                'value' => '1',
            ],
            [
                'key' => 'theme',
                'value' => $theme,
            ],
            [
                'key' => 'admin_logo',
                'value' => 'general/logo-light.png',
            ],
            [
                'key' => 'admin_favicon',
                'value' => 'general/favicon.png',
            ],
        ]);

        $data = [
            [
                'key' => 'site_title',
                'value' => 'JobBox - Laravel Job Board Script',
            ],
            [
                'key' => 'seo_description',
                'value' => 'JobBox is a neat, clean and professional job board website script for your organization. It’s easy to build a complete Job Board site with JobBox script.',
            ],
            [
                'key' => 'copyright',
                'value' => '©' . Carbon::now()->format('Y') . ' Archi Elite JSC. All right reserved.',
            ],
            [
                'key' => 'favicon',
                'value' => 'general/favicon.png',
            ],
            [
                'key' => 'logo',
                'value' => 'general/logo.png',
            ],
            [
                'key' => 'hotline',
                'value' => '+(123) 345-6789',
            ],
            [
                'key' => 'cookie_consent_message',
                'value' => 'Your experience on this site will be improved by allowing cookies ',
            ],
            [
                'key' => 'cookie_consent_learn_more_url',
                'value' => '/cookie-policy',
            ],
            [
                'key' => 'cookie_consent_learn_more_text',
                'value' => 'Cookie Policy',
            ],
            [
                'key' => 'homepage_id',
                'value' => Page::query()->value('id'),
            ],
            [
                'key' => 'blog_page_id',
                'value' => 13,
            ],
            [
                'key' => 'preloader_enabled',
                'value' => 'no',
            ],
            [
                'key' => 'job_categories_page_id',
                'value' => 18,
            ],
            [
                'key' => 'job_candidates_page_id',
                'value' => 9,
            ],
            [
                'key' => 'default_company_cover_image',
                'value' => 'general/cover-image.png',
            ],
            [
                'key' => 'job_companies_page_id',
                'value' => 8,
            ],
            [
                'key' => 'job_list_page_id',
                'value' => 7,
            ],
            [
                'key' => 'email',
                'value' => 'contact@jobbox.com',
            ],
            [
                'key' => '404_page_image',
                'value' => 'general/404.png',
            ],
            [
                'key' => 'background_breadcrumb',
                'value' => 'pages/bg-breadcrumb.png',
            ],
            [
                'key' => 'blog_page_template_blog',
                'value' => 'blog_gird_1',
            ],
            [
                'key' => 'background_blog_single',
                'value' => 'pages/img-single.png',
            ],
            [
                'key' => 'auth_background_image_1',
                'value' => 'authentication/img-1.png',
            ],
            [
                'key' => 'auth_background_image_2',
                'value' => 'authentication/img-2.png',
            ],
            [
                'key' => 'background_cover_candidate_default',
                'value' => 'pages/background-cover-candidate.png',
            ],
            [
                'key' => 'job_board_max_salary_filter',
                'value' => 10000,
            ],
        ];

        foreach ($data as $item) {
            $item['key'] = 'theme-' . $theme . '-' . $item['key'];

            if (BaseModel::determineIfUsingUuidsForId()) {
                $item['id'] = BaseModel::newUniqueId();
            }

            Setting::query()->insertOrIgnore($item);
        }

        $socialLinks = [
            [
                [
                    'key' => 'social-name',
                    'value' => 'Facebook',
                ],
                [
                    'key' => 'social-icon',
                    'value' => 'socials/facebook.png',
                ],
                [
                    'key' => 'social-url',
                    'value' => 'https://facebook.com',
                ],
            ],
            [
                [
                    'key' => 'social-name',
                    'value' => 'Linkedin',
                ],
                [
                    'key' => 'social-icon',
                    'value' => 'socials/linkedin.png',
                ],
                [
                    'key' => 'social-url',
                    'value' => 'https://linkedin.com',
                ],
            ],
            [
                [
                    'key' => 'social-name',
                    'value' => 'Twitter',
                ],
                [
                    'key' => 'social-icon',
                    'value' => 'socials/twitter.png',
                ],
                [
                    'key' => 'social-url',
                    'value' => 'https://twitter.com',
                ],
            ],
        ];

        Setting::query()->insertOrIgnore([
            'key' => 'theme-' . $theme . '-social_links',
            'value' => json_encode($socialLinks),
        ]);
    }
}
