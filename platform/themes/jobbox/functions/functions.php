<?php

use Botble\Base\Facades\MetaBox;
use Botble\Base\Forms\FieldOptions\CheckboxFieldOption;
use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Base\Rules\OnOffRule;
use Botble\Blog\Models\Post;
use Botble\JobBoard\Forms\AccountForm;
use Botble\JobBoard\Forms\Fronts\AccountSettingForm;
use Botble\JobBoard\Forms\JobForm;
use Botble\JobBoard\Forms\Settings\GeneralSettingForm;
use Botble\JobBoard\Http\Requests\SettingRequest;
use Botble\JobBoard\Http\Requests\Settings\GeneralSettingRequest;
use Botble\JobBoard\Models\Account;
use Botble\JobBoard\Models\Category;
use Botble\JobBoard\Models\Job;
use Botble\Media\Facades\RvMedia;
use Botble\Menu\Facades\Menu;
use Botble\Page\Models\Page;
use Botble\Theme\Facades\Theme;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

register_page_template([
    'default' => __('Default'),
    'page-detail' => __('Page detail full width'),
    'page-detail-boxed' => __('Page detail boxed'),
    'homepage' => __('Homepage'),
]);

register_sidebar([
    'id' => 'footer_sidebar',
    'name' => __('Footer sidebar'),
    'description' => __('Widgets in footer of page'),
]);

register_sidebar([
    'id' => 'pre_footer_sidebar',
    'name' => __('Pre footer sidebar'),
    'description' => __('Widgets at the bottom of the page.'),
]);

register_sidebar([
    'id' => 'blog_sidebar',
    'name' => __('Blog sidebar'),
    'description' => __('Widgets at the right of the page.'),
]);

register_sidebar([
    'id' => 'candidate_sidebar',
    'name' => __('Candidate sidebar'),
    'description' => __('Widgets at the right of the page candidate detail.'),
]);

register_sidebar([
    'id' => 'company_sidebar',
    'name' => __('Company sidebar'),
    'description' => __('Widgets at the right of the page company detail.'),
]);

Menu::addMenuLocation('footer-menu', 'Footer navigation');

app()->booted(function (): void {
    RvMedia::addSize('featured', 403, 257);

    if (is_plugin_active('job-board')) {
        AccountSettingForm::beforeRendering(function (AccountSettingForm $form) {
            return $form->remove(['slug']);
        });

        AccountSettingForm::beforeSaving(function (AccountSettingForm $form): void {
            $request = $form->getRequest();
            $model = $form->getModel();

            if ($request->has('cover_image')) {
                $coverImageUrl = $request->input('cover_image');
                if ($request->hasFile('cover_image')) {
                    $result = RvMedia::handleUpload($request->file('cover_image'), 0, $model->upload_folder);

                    $coverImageUrl = $result['data']->url;
                }

                MetaBox::saveMetaBoxData($model, 'cover_image', $coverImageUrl);
            }
        });

        FormAbstract::extend(function (FormAbstract $form) {
            if ($form instanceof AccountSettingForm || $form instanceof AccountForm) {
                $form
                    ->addAfter(
                        'description',
                        'linkedin',
                        TextField::class,
                        TextFieldOption::make()
                            ->label(__('LinkedIn URL'))
                            ->metadata()
                            ->toArray()
                    );
            }

            if ($form instanceof AccountForm) {
                $form->add(
                    'cover_image',
                    MediaImageField::class,
                    MediaImageFieldOption::make()
                        ->label(__('Cover Image'))
                        ->metadata()
                        ->toArray()
                );
            }

            return $form;
        });

        add_filter('core_request_rules', function (array $rules, Request $request) {
            if ($request instanceof SettingRequest) {
                return array_merge($rules, [
                    'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,bmp|max:' . ceil(RvMedia::getServerConfigMaxUploadFileSize() / 1024),
                ]);
            }

            return $rules;
        }, 120, 2);

        FormAbstract::extend(function (FormAbstract $form) {
            if ($form instanceof JobForm) {
                $form->addAfter(
                    'apply_url',
                    'is_direct_redirect',
                    OnOffField::class,
                    CheckboxFieldOption::make()
                        ->defaultValue(setting('job_board_enabled_default_direct_redirect_apply_job_from_url', true))
                        ->metadata()
                        ->helperText(__('If you enable this option, the apply button will redirect to the apply URL directly.'))
                        ->label(__('Is direct redirect?'))
                );
            }

            return $form;
        });

        GeneralSettingForm::extend(function (GeneralSettingForm $form) {
            $form->add(
                'job_board_enabled_default_direct_redirect_apply_job_from_url',
                OnOffCheckboxField::class,
                CheckboxFieldOption::make()
                    ->value(setting('job_board_enabled_default_direct_redirect_apply_job_from_url', true))
                    ->label(__('Enable default direct redirection when applying from URL?'))
            );
        });

        add_filter('core_request_rules', function (array $rules, Request $request) {
            if ($request instanceof GeneralSettingRequest) {
                return [...$rules,
                    'job_board_enabled_default_direct_redirect_apply_job_from_url' => new OnOffRule(),
                ];
            }

            return $rules;
        }, 120, 2);
    }

    add_filter(BASE_FILTER_BEFORE_RENDER_FORM, function (FormAbstract $form, ?Model $data) {
        switch (get_class($data)) {
            case Category::class:
                $form
                    ->addAfter('status', 'job_category_image', 'mediaImage', [
                        'label' => __('Image'),
                        'value' => MetaBox::getMetaData($data, 'job_category_image', true),
                    ])
                    ->addAfter('job_category_image', 'icon_image', 'mediaImage', [
                        'label' => __('Icon Image'),
                        'value' => MetaBox::getMetaData($data, 'icon_image', true),
                    ]);

                break;
            case Post::class:
                $form
                    ->add('cover_image', 'mediaImage', [
                        'label' => __('Cover Image'),
                        'label_attr' => ['class' => 'control-label'],
                        'value' => MetaBox::getMetaData($data, 'cover_image', true),
                    ])
                    ->addAfter('status', 'time_to_read', 'number', [
                        'label' => __('Time to read'),
                        'value' => MetaBox::getMetaData($data, 'time_to_read', true),
                        'attr' => [
                            'placeholder' => __('Time to read (minute)'),
                            'class' => ['image-data form-control'],
                        ],
                    ]);

                break;
            case Page::class:
                $form
                    ->add('background_breadcrumb', 'mediaImage', [
                        'label' => __('Background Breadcrumb'),
                        'label_attr' => ['class' => 'control-label'],
                        'value' => MetaBox::getMetaData($data, 'background_breadcrumb', true),
                    ]);

                break;
            case Job::class:
                if (auth()->check()) {
                    $form
                        ->addBefore('categories[]', 'featured_image', 'mediaImage', [
                            'label' => __('Featured Image'),
                            'label_attr' => ['class' => 'control-label'],
                            'value' => MetaBox::getMetaData($data, 'featured_image', true),
                        ]);
                } else {
                    $form
                        ->addAfter('status', 'featured_image', 'mediaImage', [
                            'label' => __('Featured Image'),
                            'label_attr' => ['class' => 'control-label'],
                            'value' => MetaBox::getMetaData($data, 'featured_image', true),
                        ]);
                }

                break;
        }

        return $form;
    }, 120, 3);

    add_action([BASE_ACTION_AFTER_CREATE_CONTENT, BASE_ACTION_AFTER_UPDATE_CONTENT], function (string $screen, Request $request, $data): void {
        if ($data instanceof Post && $request->has('time_to_read')) {
            MetaBox::saveMetaBoxData($data, 'time_to_read', $request->input('time_to_read'));
        }

        if ($data instanceof Category) {
            if ($request->has('job_category_image')) {
                MetaBox::saveMetaBoxData($data, 'job_category_image', $request->input('job_category_image'));
            }

            if ($request->has('icon_image')) {
                MetaBox::saveMetaBoxData($data, 'icon_image', $request->input('icon_image'));
            }
        }

        if ($data instanceof Post) {
            MetaBox::saveMetaBoxData($data, 'cover_image', $request->input('cover_image'));
        }

        if ($data instanceof Page) {
            MetaBox::saveMetaBoxData($data, 'background_breadcrumb', $request->input('background_breadcrumb'));
        }

        if ($data instanceof Job) {
            if ($request->has('featured_image')) {
                MetaBox::saveMetaBoxData($data, 'featured_image', $request->input('featured_image'));
            }

            if ($request->has('is_direct_redirect')) {
                MetaBox::saveMetaBoxData($data, 'is_direct_redirect', $request->input('is_direct_redirect'));
            }
        }
    }, 120, 3);

    add_filter('account_settings_page', function (?string $html, Account $account) {
        return $html . Theme::partial('account-custom-fields', compact('account'));
    }, 127, 2);

    if (is_plugin_active('ads')) {
        add_filter('ads_locations', function (array $locations) {
            return [
                ...$locations,
                'main_content_before' => __('Main Content (before)'),
                'main_content_after' => __('Main Content (after)'),
                'blog_sidebar_before' => __('Blog Sidebar (before)'),
                'blog_sidebar_after' => __('Blog Sidebar (after)'),
                'company_sidebar_before' => __('Company Sidebar (before)'),
                'company_sidebar_after' => __('Company Sidebar (after)'),
                'candidate_sidebar_before' => __('Candidate Sidebar (before)'),
                'candidate_sidebar_after' => __('Candidate Sidebar (after)'),
                'footer_before' => __('Footer (before)'),
                'footer_after' => __('Footer (after)'),
                'post_list_before' => __('Post List (before)'),
                'post_list_after' => __('Post List (after)'),
                'post_before' => __('Post Detail (before)'),
                'post_after' => __('Post Detail (after)'),
                'job_list_before' => __('Job List (before)'),
                'job_list_after' => __('Job List (after)'),
                'job_before' => __('Job Detail (before)'),
                'job_after' => __('Job Detail (after)'),
                'company_list_before' => __('Company List (before)'),
                'company_list_after' => __('Company List (after)'),
                'company_before' => __('Company Detail (before)'),
                'company_after' => __('Company Detail (after)'),
                'candidate_list_before' => __('Candidate List (before)'),
                'candidate_list_after' => __('Candidate List (after)'),
                'candidate_before' => __('Candidate Detail (before)'),
                'candidate_after' => __('Candidate Detail (after)'),
            ];
        }, 128);
    }
});

if (! function_exists('get_currencies_json')) {
    function get_currencies_json(): array
    {
        $currency = get_application_currency();
        $numberAfterDot = $currency->decimals ?: 0;

        $thousandsSeparator = setting('job_board_thousands_separator', ',');

        return [
            'display_big_money' => config('plugins.real-estate.real-estate.display_big_money_in_million_billion'),
            'billion' => __('billion'),
            'million' => __('million'),
            'is_prefix_symbol' => $currency->is_prefix_symbol,
            'symbol' => $currency->symbol,
            'title' => $currency->title,
            'decimal_separator' => setting('job_board_decimal_separator', '.'),
            'thousands_separator' => $thousandsSeparator === 'space' ? ' ' : $thousandsSeparator,
            'number_after_dot' => $numberAfterDot,
            'show_symbol_or_title' => true,
        ];
    }
}
