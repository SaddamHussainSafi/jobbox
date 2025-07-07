<?php

use Botble\Base\Facades\Assets;
use Botble\Base\Facades\Html;
use Botble\Base\Forms\FieldOptions\ButtonFieldOption;
use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Blog\Repositories\Interfaces\PostInterface;
use Botble\Contact\Forms\Fronts\ContactForm;
use Botble\Contact\Forms\ShortcodeContactAdminConfigForm;
use Botble\Faq\Models\Faq;
use Botble\Faq\Models\FaqCategory;
use Botble\JobBoard\Enums\AccountTypeEnum;
use Botble\JobBoard\Facades\JobBoardHelper;
use Botble\JobBoard\Models\Account;
use Botble\JobBoard\Models\Category;
use Botble\JobBoard\Models\Company;
use Botble\JobBoard\Models\Job;
use Botble\JobBoard\Models\Package;
use Botble\JobBoard\Repositories\Interfaces\CategoryInterface;
use Botble\JobBoard\Repositories\Interfaces\JobInterface;
use Botble\Location\Facades\Location;
use Botble\Location\Models\City;
use Botble\Location\Models\State;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Shortcode\Compilers\Shortcode;
use Botble\Team\Models\Team;
use Botble\Testimonial\Models\Testimonial;
use Botble\Theme\Facades\Theme;
use Botble\Theme\Supports\ThemeSupport;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

app()->booted(function (): void {
    ThemeSupport::registerGoogleMapsShortcode();
    ThemeSupport::registerYoutubeShortcode();

    if (is_plugin_active('job-board')) {
        add_shortcode('search-box', __('Search box'), __('The big search box'), function (Shortcode $shortcode) {
            if ($shortcode->style === 'style-2') {
                $with = [
                    'slugable',
                    'metadata',
                ];

                $featuredCompanies = Company::query()
                    ->wherePublished()
                    ->where('is_featured', true)
                    ->with($with)
                    ->take((int) $shortcode->limit_company ?: Arr::first(JobBoardHelper::getPerPageParams()))->latest()
                    ->get();

                return Theme::partial('shortcodes.search-box', compact('shortcode', 'featuredCompanies'));
            }

            if ($shortcode->style === 'style-3') {
                $categories = Category::query()
                    ->with(['slugable', 'metadata'])
                    ->withCount(['activeJobs as jobs_count'])
                    ->wherePublished()
                    ->get();

                return Theme::partial('shortcodes.search-box', compact('shortcode', 'categories'));
            }

            return Theme::partial('shortcodes.search-box', compact('shortcode'));
        });

        shortcode()->setAdminConfig('search-box', function (array $attributes) {
            return Theme::partial('shortcodes.search-box-admin-config', compact('attributes'));
        });

        add_shortcode(
            'featured-job-categories',
            __('Featured job categories'),
            __('Featured job categories'),
            function (Shortcode $shortcode) {
                $categories = Category::query()
                    ->wherePublished()
                    ->where('is_featured', true)
                    ->orderBy('order')->latest()
                    ->withCount(['activeJobs'])
                    ->with([
                        'slugable',
                        'metadata',
                    ])
                    ->limit((int) $shortcode->limit_category ?: Arr::first(JobBoardHelper::getPerPageParams()))
                    ->get();

                return Theme::partial('shortcodes.featured-job-categories', compact('shortcode', 'categories'));
            }
        );

        shortcode()->setAdminConfig('featured-job-categories', function (array $attributes) {
            return Theme::partial('shortcodes.featured-job-categories-admin-config', compact('attributes'));
        });

        add_shortcode('job-categories', __('Job categories'), __('Job categories'), function (Shortcode $shortcode) {
            $categories = Category::query()
                ->wherePublished()
                ->withCount(['activeJobs as jobs_count'])
                ->paginate((int) $shortcode->limit_category ?: Arr::first(JobBoardHelper::getPerPageParams()));

            return Theme::partial('shortcodes.job-categories', compact('shortcode', 'categories'));
        });

        shortcode()->setAdminConfig('job-categories', function (array $attributes) {
            return Theme::partial('shortcodes.job-categories-admin-config', compact('attributes'));
        });

        add_shortcode(
            'apply-banner',
            __('Apply banner'),
            __('Apply banner show form apply'),
            function (Shortcode $shortcode) {
                $subtitleText = '';

                if ($shortcode->highlight_sub_title_text) {
                    $oldHighLightText = explode(',', $shortcode->highlight_sub_title_text);
                    $newHighlightText = array_map(function ($value) {
                        return '<span class="color-brand-1">' . $value . '</span>';
                    }, $oldHighLightText);

                    $subtitleText = str_replace($oldHighLightText, $newHighlightText, $shortcode->subtitle);
                }

                return Theme::partial('shortcodes.apply-banner', compact('shortcode', 'subtitleText'));
            }
        );

        shortcode()->setAdminConfig('apply-banner', function (array $attributes) {
            return Theme::partial('shortcodes.apply-banner-admin-config', compact('attributes'));
        });

        add_shortcode('job-tabs', __('Job tabs'), __('Job tabs'), function (Shortcode $shortcode) {
            $with = [
                'slugable',
                'jobTypes',
                'company',
                'company.slugable',
                'jobExperience',
            ];

            if (is_plugin_active('location')) {
                $with = array_merge($with, array_keys(Location::getSupported(Job::class)));
            }

            $featuredJobs = app(JobInterface::class)->getFeaturedJobs(10, $with);
            $recentJobs = app(JobInterface::class)->getRecentJobs(10, $with);
            $popularJobs = app(JobInterface::class)->getPopularJobs(10, $with);

            return Theme::partial(
                'shortcodes.job-tabs',
                compact('shortcode', 'featuredJobs', 'recentJobs', 'popularJobs')
            );
        });

        shortcode()->setAdminConfig('job-tabs', function (array $attributes) {
            return Theme::partial('shortcodes.job-tabs-admin-config', compact('attributes'));
        });

        add_shortcode('job-of-the-day', __('Job of the day'), __('Job of the day'), function (Shortcode $shortcode) {
            $categoryIds = [];

            if ($shortcode->job_categories) {
                $categoryIds = explode(',', $shortcode->job_categories);
            }

            if (empty($categoryIds)) {
                return null;
            }

            $categories = Category::query()
                ->wherePublished()
                ->whereIn('id', $categoryIds)
                ->get();

            $with = [
                'slugable',
                'company',
                'company.slugable',
                'jobTypes',
                'tags',
                'tags.slugable',
                'skills',
            ];

            if ((is_plugin_active('location'))) {
                $with = array_merge($with, [
                    'country',
                    'state',
                    'city',
                ]);
            }

            $jobs = app(JobInterface::class)
                ->getJobs(
                    [
                        'job_categories' => $categories->pluck('id')->all(),
                    ],
                    [
                        'with' => $with,
                        'take' => (int) $shortcode->limit ?: 8,
                    ]
                );

            return Theme::partial('shortcodes.job-of-the-day', compact('shortcode', 'categories', 'jobs'));
        });

        Assets::addStylesDirectly('vendor/core/core/base/libraries/tagify/tagify.css');

        shortcode()->setAdminConfig('job-of-the-day', function (array $attributes) {
            $categories = Category::query()
                ->wherePublished()
                ->pluck('name', 'id')
                ->all();

            return Html::script('vendor/core/core/base/libraries/tagify/tagify.js') .
                Html::script('vendor/core/core/base/js/tags.js') .
                Theme::partial('shortcodes.job-of-the-day-admin-config', compact('attributes', 'categories'));
        });

        add_shortcode('job-grid', __('Job grid banner'), __('Job grid banner'), function (Shortcode $shortcode) {
            return Theme::partial('shortcodes.job-grid', compact('shortcode'));
        });

        add_shortcode(
            'company-information',
            __('Company Information'),
            __('Company Information'),
            function (Shortcode $shortcode) {
                return Theme::partial('shortcodes.company-information', compact('shortcode'));
            }
        );

        shortcode()->setAdminConfig('company-information', function (array $attributes) {
            return Theme::partial('shortcodes.company-information-admin-config', compact('attributes'));
        });

        add_shortcode('company-about', __('Company About'), __('Company About'), function (Shortcode $shortcode) {
            return Theme::partial('shortcodes.company-about', compact('shortcode'));
        });

        shortcode()->setAdminConfig('company-about', function (array $attributes) {
            return Theme::partial('shortcodes.company-about-admin-config', compact('attributes'));
        });

        add_shortcode('job-grid', __('Job grid banner'), __('Job grid banner'), function (Shortcode $shortcode) {
            return Theme::partial('shortcodes.job-grid', compact('shortcode'));
        });

        shortcode()->setAdminConfig('job-grid', function (array $attributes) {
            return Theme::partial('shortcodes.job-grid-admin-config', compact('attributes'));
        });

        if (is_plugin_active('location')) {
            add_shortcode(
                'job-by-location',
                __('Job by location'),
                __('Job by location'),
                function (Shortcode $shortcode) {
                    $cityIds = array_filter(explode(',', $shortcode->city));
                    $stateIds = array_filter(explode(',', $shortcode->state));

                    if (empty($cityIds) && empty($stateIds)) {
                        return null;
                    }

                    $cities = collect();
                    $states = collect();

                    if (! empty($cityIds)) {
                        City::resolveRelationUsing('companies', function ($model) {
                            return $model->hasMany(Company::class, 'city_id');
                        });

                        City::resolveRelationUsing('jobs', function ($model) {
                            return $model->hasMany(Job::class, 'city_id');
                        });

                        $cities = City::query()
                            ->whereIn('id', $cityIds)
                            ->withCount([
                                'companies',
                                'jobs' => function (Builder $query): void {
                                    $query
                                        ->active()
                                        ->addApplied()
                                        ->orderBy('is_featured', 'DESC')
                                        ->latest();
                                },
                            ])
                            ->with(['country', 'metadata'])
                            ->take(6)
                            ->get();
                    }

                    if (! empty($stateIds)) {
                        State::resolveRelationUsing('companies', function ($model) {
                            return $model->hasMany(Company::class, 'state_id');
                        });

                        State::resolveRelationUsing('jobs', function ($model) {
                            return $model->hasMany(Job::class, 'state_id');
                        });

                        $states = State::query()
                            ->whereIn('id', $stateIds)
                            ->withCount([
                                'companies',
                                'jobs' => function (Builder $query): void {
                                    $query
                                        ->active()
                                        ->addApplied()
                                        ->orderBy('is_featured', 'DESC')
                                        ->latest();
                                },
                            ])
                            ->with(['country', 'metadata'])
                            ->take(6)
                            ->get();
                    }

                    $locations = $cities->merge($states);

                    $title = $shortcode->title;
                    $description = $shortcode->description;
                    $style = $shortcode->style;

                    return Theme::partial(
                        'shortcodes.job-by-location',
                        compact('title', 'description', 'style', 'states', 'locations')
                    );
                }
            );

            shortcode()->setAdminConfig('job-by-location', function (array $attributes) {
                $cities = City::query()
                    ->wherePublished()
                    ->pluck('name', 'id');

                $states = State::query()
                    ->wherePublished()
                    ->pluck('name', 'id');

                return Html::script('vendor/core/core/base/libraries/tagify/tagify.js') .
                    Html::script('vendor/core/core/base/js/tags.js') .
                    Theme::partial(
                        'shortcodes.job-by-location-admin-config',
                        compact('attributes', 'cities', 'states')
                    );
            });
        }

        add_shortcode('news-and-blogs', __('News and blog'), __('News and blog'), function (Shortcode $shortcode) {
            $posts = app(PostInterface::class)
                ->getFeatured(6, [
                    'slugable',
                    'tags',
                    'tags.slugable',
                    'metadata',
                    'author',
                ]);

            return Theme::partial('shortcodes.news-and-blogs', compact('shortcode', 'posts'));
        });

        shortcode()->setAdminConfig('news-and-blogs', function (array $attributes) {
            return Theme::partial('shortcodes.news-and-blogs-admin-config', compact('attributes'));
        });

        add_shortcode('pricing-table', __('Pricing Table'), __('Pricing Table'), function (Shortcode $shortcode) {
            $packages = Package::query()
                ->wherePublished()
                ->orderByDesc('created_at')
                ->take((int) $shortcode->number_of_package ?: 6)
                ->get();

            return Theme::partial('shortcodes.pricing-table', compact('shortcode', 'packages'));
        });

        shortcode()->setAdminConfig('pricing-table', function (array $attributes) {
            return Theme::partial('shortcodes.pricing-table-admin-config', compact('attributes'));
        });

        shortcode()->setAdminConfig('job-grid', function (array $attributes) {
            return Theme::partial('shortcodes.job-grid-admin-config', compact('attributes'));
        });

        add_shortcode(
            'top-companies',
            __('Top companies table'),
            __('Top companies table'),
            function (Shortcode $shortcode) {
                $with = ['slugable'];

                if (is_plugin_active('location')) {
                    $with = array_merge($with, array_keys(Location::getSupported(Job::class)));
                }

                $companies = Company::query()
                    ->where('is_featured', 1)
                    ->with($with)
                    ->withCount([
                        'reviews',
                        'activeJobs as jobs_count',
                    ])
                    ->withAvg('reviews', 'star')
                    ->take(15)->latest()
                    ->get();

                return Theme::partial('shortcodes.top-companies', compact('shortcode', 'companies'));
            }
        );

        shortcode()->setAdminConfig('top-companies', function (array $attributes) {
            return Theme::partial('shortcodes.top-companies-admin-config', compact('attributes'));
        });

        add_shortcode(
            'popular-category',
            __('Popular category'),
            __('Popular category'),
            function (Shortcode $shortcode) {
                $categories = app(CategoryInterface::class)
                    ->getFeaturedCategories($shortcode->limit_category ?: 10);

                $categories->loadCount('activeJobs');

                return Theme::partial('shortcodes.popular-category', compact('shortcode', 'categories'));
            }
        );

        shortcode()->setAdminConfig('popular-category', function (array $attributes) {
            return Theme::partial('shortcodes.popular-category-admin-config', compact('attributes'));
        });

        add_shortcode(
            'advertisement-banner',
            __('Advertisement banner'),
            __('Advertisement banner'),
            function (Shortcode $shortcode) {
                return Theme::partial('shortcodes.advertisement-banner', compact('shortcode'));
            }
        );

        shortcode()->setAdminConfig('advertisement-banner', function (array $attributes) {
            return Theme::partial('shortcodes.advertisement-banner-admin-config', compact('attributes'));
        });

        add_shortcode('counter-section', __('Counter section'), __('Counter section'), function (Shortcode $shortcode) {
            return Theme::partial('shortcodes.counter-section', compact('shortcode'));
        });

        shortcode()->setAdminConfig('counter-section', function (array $attributes) {
            return Theme::partial('shortcodes.counter-section-admin-config', compact('attributes'));
        });

        add_shortcode('our-partners', __('Box Trust'), __('Box Trust'), function (Shortcode $shortcode) {
            return Theme::partial('shortcodes.our-partners', compact('shortcode'));
        });

        shortcode()->setAdminConfig('our-partners', function (array $attributes) {
            return Theme::partial('shortcodes.our-partners-admin-config', compact('attributes'));
        });

        add_shortcode('top-candidates', __('Top Candidates'), __('Top Candidates'), function (Shortcode $shortcode) {
            if (JobBoardHelper::isDisabledPublicProfile()) {
                $candidates = collect();
            } else {
                $candidates = Account::query()
                    ->with('slugable')
                    ->withCount('reviews')
                    ->withAvg('reviews', 'star')
                    ->where('is_featured', 1)
                    ->where('is_public_profile', 1)
                    ->where('type', AccountTypeEnum::JOB_SEEKER)
                    ->limit($shortcode->limit ?: 8)
                    ->latest()
                    ->get();
            }

            return Theme::partial('shortcodes.top-candidates', compact('shortcode', 'candidates'));
        });

        shortcode()->setAdminConfig('top-candidates', function (array $attributes) {
            return Theme::partial('shortcodes.top-candidates-admin-config', compact('attributes'));
        });

        add_shortcode('job-list', __('Job list'), __('Show job list'), function (Shortcode $shortcode) {
            $requestQuery = JobBoardHelper::getJobFilters(request()->input());

            if (! empty($requestQuery['keyword'])) {
                SeoHelper::setTitle(__('Search results for ":keyword"', ['keyword' => $requestQuery['keyword']]));

                if (! empty($requestQuery['job_categories'])) {
                    $categories = Category::query()
                        ->whereIn('id', $requestQuery['job_categories'])
                        ->pluck('name')
                        ->all();

                    if ($categories) {
                        SeoHelper::setTitle(__('Search results for ":keyword" in :categories', ['keyword' => $requestQuery['keyword'], 'categories' => implode(', ', $categories)]));
                    }
                }
            }

            $with = [
                'tags.slugable',
                'jobTypes',
                'slugable',
                'jobExperience',
                'company',
                'company.metadata',
                'company.slugable',
            ];

            $sortBy = match (request()->input('sort_by') ?: 'newest') {
                'oldest' => [
                    'jb_jobs.created_at' => 'ASC',
                ],
                default => [
                    'jb_jobs.created_at' => 'DESC',
                ],
            };

            if (JobBoardHelper::isPinFeaturedJobsInTheTop()) {
                $sortBy = ['jb_jobs.is_featured' => 'DESC', ...$sortBy];
            }

            if (is_plugin_active('location')) {
                $with = array_merge($with, array_keys(Location::getSupported(Job::class)));
            }

            $jobs = app(JobInterface::class)->getJobs(
                $requestQuery,
                [
                    'with' => $with,
                    'order_by' => $sortBy,
                    'paginate' => [
                        'per_page' => (int) $shortcode->jobs_per_page ?: Arr::get($requestQuery, 'per_page'),
                        'current_paged' => Arr::get($requestQuery, 'page') ?: 1,
                    ],
                ],
            );

            if (! $shortcode->jobs_per_page_options) {
                $perPages = JobBoardHelper::getPerPageParams();
            } else {
                $perPages = array_map(
                    'trim',
                    array_filter(explode(',', $shortcode->jobs_per_page_options), 'is_numeric')
                );
            }

            return Theme::partial(
                'shortcodes.job-list',
                compact(
                    'shortcode',
                    'jobs',
                    'perPages',
                )
            );
        });

        shortcode()->setAdminConfig('job-list', function (array $attributes) {
            return Theme::partial('shortcodes.job-list-admin-config', compact('attributes'));
        });

        add_shortcode('job-companies', __('Company list'), __('Company list'), function (Shortcode $shortcode) {
            $requestQuery = JobBoardHelper::getCompanyFilterParams(request()->input());

            $with = [
                'slugable',
                'metadata',
            ];

            if (is_plugin_active('location')) {
                $with = array_merge($with, array_keys(Location::getSupported(Company::class)));
            }

            $companies = Company::query()
                ->withCount([
                    'reviews',
                    'activeJobs as jobs_count',
                ]);

            if (JobBoardHelper::isPinFeaturedCompaniesInTheTop()) {
                $companies = $companies->orderByDesc('is_featured');
            }

            match ($requestQuery['sort_by'] ?? 'oldest') {
                'newest' => $companies = $companies->orderByDesc('created_at'),
                default => $companies = $companies->orderBy('created_at'),
            };

            if (! empty($requestQuery['keyword'])) {
                $companies = $companies->where('name', 'LIKE', $requestQuery['keyword'] . '%');
            }

            $companies = $companies->with($with)
                ->wherePublished()
                ->withAvg('reviews', 'star')
                ->paginate($requestQuery['per_page'] ?: Arr::first(JobBoardHelper::getPerPageParams()));

            return Theme::partial('shortcodes.job-companies', compact('shortcode', 'companies'));
        });

        shortcode()->setAdminConfig('job-companies', function (array $attributes) {
            return Theme::partial('shortcodes.job-companies-admin-config', compact('attributes'));
        });
    }

    if (is_plugin_active('contact')) {
        add_filter(CONTACT_FORM_TEMPLATE_VIEW, function () {
            return Theme::getThemeNamespace('partials.shortcodes.contact-form');
        }, 99);

        ContactForm::beforeRendering(function (ContactForm $form) {
            return $form
                ->remove('submit')
                ->add(
                    'submit',
                    'submit',
                    ButtonFieldOption::make()
                       ->label(__('Send Message'))
                       ->attributes(['class' => 'submit btn btn-send-message'])
                       ->toArray(),
                );
        });

        shortcode()->modifyAdminConfig('contact-form', function (ShortcodeContactAdminConfigForm $form) {
            return $form
                ->add(
                    'title',
                    TextField::class,
                    TextFieldOption::make()
                        ->label(__('Title'))
                        ->toArray()
                )
                ->add(
                    'subtitle',
                    TextField::class,
                    TextFieldOption::make()
                        ->label(__('Subtitle'))
                        ->toArray()
                )
                ->add('description', TextareaField::class, DescriptionFieldOption::make()->toArray())
                ->add('image', MediaImageField::class);
        });
    }

    if (is_plugin_active('team')) {
        add_shortcode('team', __('Team'), __('Team'), function (Shortcode $shortcode) {
            $teams = Team::query()
                ->wherePublished()
                ->limit((int) $shortcode->number_of_people ?: 6)->latest()
                ->get();

            return Theme::partial('shortcodes.team', compact('shortcode', 'teams'));
        });

        shortcode()->setAdminConfig('team', function (array $attributes) {
            return Theme::partial('shortcodes.team-admin-config', compact('attributes'));
        });
    }

    if (is_plugin_active('faq')) {
        add_shortcode('faq', __('FAQ'), __('FAQ'), function (Shortcode $shortcode) {
            if ($categoryIds = $shortcode->category_ids) {
                $categoryIds = explode(',', $categoryIds);

                if (! empty($categoryIds)) {
                    $faqCategories = FaqCategory::query()
                        ->whereIn('id', $categoryIds)
                        ->wherePublished()
                        ->latest()
                        ->get();
                } else {
                    $faqCategories = collect();
                }

                $faqs = collect();
            } else {
                $faqs = Faq::query()
                    ->wherePublished()
                    ->orderByDesc('created_at')
                    ->limit((int) $shortcode->number_of_faq ?: 6)
                    ->get();

                $faqCategories = collect();
            }

            return Theme::partial('shortcodes.faq', compact('shortcode', 'faqCategories', 'faqs'));
        });

        shortcode()->setAdminConfig('faq', function (array $attributes) {
            $categories = FaqCategory::query()
                ->wherePublished()
                ->get();

            return Theme::partial('shortcodes.faq-admin-config', compact('attributes', 'categories'));
        });
    }

    add_shortcode('gallery', __('Gallery'), __('Gallery'), function (Shortcode $shortcode) {
        return Theme::partial('shortcodes.gallery', compact('shortcode'));
    });

    shortcode()->setAdminConfig('gallery', function (array $attributes) {
        return Theme::partial('shortcodes.gallery-admin-config', compact('attributes'));
    });

    add_shortcode(
        'job-search-banner',
        __('Job search banner'),
        __('Job search banner'),
        function (Shortcode $shortcode) {
            return Theme::partial('shortcodes.job-search-banner', compact('shortcode'));
        }
    );

    shortcode()->setAdminConfig('job-search-banner', function (array $attributes) {
        return Theme::partial('shortcodes.job-search-banner-admin-config', compact('attributes'));
    });

    add_shortcode('how-it-works', __('How It Works'), __('How It Works'), function (Shortcode $shortcode) {
        return Theme::partial('shortcodes.how-it-works', compact('shortcode'));
    });

    shortcode()->setAdminConfig('how-it-works', function (array $attributes) {
        return Theme::partial('shortcodes.how-it-works-admin-config', compact('attributes'));
    });

    add_shortcode('job-candidates', __('Job Candidates'), __('Job Candidates'), function (Shortcode $shortcode) {
        $candidates = new LengthAwarePaginator(collect(), 0, Arr::first(JobBoardHelper::getPerPageParams()));
        if (! JobBoardHelper::isDisabledPublicProfile()) {
            $candidates = JobBoardHelper::filterCandidates(request()->input());
        }

        return Theme::partial('shortcodes.job-candidates', compact('shortcode', 'candidates'));
    });

    shortcode()->setAdminConfig('job-candidates', function (array $attributes) {
        return Theme::partial('shortcodes.job-candidates-admin-config', compact('attributes'));
    });

    if (is_plugin_active('testimonial')) {
        add_shortcode('testimonials', __('Testimonials'), __('Testimonials'), function (Shortcode $shortcode) {
            $testimonials = Testimonial::query()
                ->wherePublished()
                ->get();

            return Theme::partial('shortcodes.testimonials', compact('shortcode', 'testimonials'));
        });

        shortcode()->setAdminConfig('testimonials', function (array $attributes) {
            return Theme::partial('shortcodes.testimonials-admin-config', compact('attributes'));
        });
    }
});
