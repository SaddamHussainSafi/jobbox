<?php

namespace Botble\JobBoard\Forms;

use Botble\Base\Facades\Assets;
use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\Html;
use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\IsFeaturedFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\FieldOptions\TreeCategoryFieldOption;
use Botble\Base\Forms\Fields\DatePickerField;
use Botble\Base\Forms\Fields\MultiCheckListField;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TagField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\Fields\TreeCategoryField;
use Botble\Base\Forms\FormAbstract;
use Botble\JobBoard\Enums\CustomFieldEnum;
use Botble\JobBoard\Enums\JobStatusEnum;
use Botble\JobBoard\Enums\ModerationStatusEnum;
use Botble\JobBoard\Enums\SalaryRangeEnum;
use Botble\JobBoard\Facades\JobBoardHelper;
use Botble\JobBoard\Http\Requests\JobRequest;
use Botble\JobBoard\Models\CareerLevel;
use Botble\JobBoard\Models\Category;
use Botble\JobBoard\Models\Currency;
use Botble\JobBoard\Models\CustomField;
use Botble\JobBoard\Models\DegreeLevel;
use Botble\JobBoard\Models\FunctionalArea;
use Botble\JobBoard\Models\Job;
use Botble\JobBoard\Models\JobExperience;
use Botble\JobBoard\Models\JobSkill;
use Botble\JobBoard\Models\JobType;
use Botble\Location\Fields\Options\SelectLocationFieldOption;
use Botble\Location\Fields\SelectLocationField;

class JobForm extends FormAbstract
{
    public function setup(): void
    {
        Assets::addScripts(['input-mask'])
            ->addScriptsDirectly('vendor/core/plugins/job-board/js/components.js')
            ->addScriptsDirectly('vendor/core/plugins/job-board/js/job.js')
            ->addScriptsDirectly('vendor/core/plugins/job-board/js/employer-colleagues.js');

        Assets::usingVueJS();

        /**
         * @var Job $model
         */
        $model = $this->getModel();

        $currencies = Currency::query()
            ->orderBy('order')
            ->oldest('title')
            ->pluck('title', 'id')
            ->all();

        $skills = JobSkill::query()
            ->wherePublished()
            ->orderBy('order')
            ->oldest('name')
            ->select('name', 'id')
            ->get()
            ->mapWithKeys(fn ($item) => [$item->id => $item->name])
            ->all();

        $selectedSkills = [];
        if ($skills && $model) {
            $selectedSkills = $model->skills()->pluck('job_skill_id')->all();
        }

        $jobTypes = JobType::query()
            ->wherePublished()
            ->orderBy('order')
            ->oldest('name')
            ->select('name', 'id')
            ->get()
            ->mapWithKeys(fn ($item) => [$item->id => $item->name])
            ->all();

        $selectedJobTypes = [];
        if ($jobTypes && $model) {
            $selectedJobTypes = $model->jobTypes()->pluck('job_type_id')->all();
        }

        $careerLevels = CareerLevel::query()
            ->wherePublished()
            ->orderBy('order')
            ->oldest('name')
            ->select('name', 'id')
            ->get()
            ->mapWithKeys(fn ($item) => [$item->id => $item->name])
            ->all();

        $degreeLevels = DegreeLevel::query()
            ->wherePublished()
            ->orderBy('order')
            ->oldest('name')
            ->select('name', 'id')
            ->get()
            ->mapWithKeys(fn ($item) => [$item->id => $item->name])
            ->all();

        $jobExperiences = JobExperience::query()
            ->wherePublished()
            ->orderBy('order')
            ->oldest('name')
            ->select('name', 'id')
            ->get()
            ->mapWithKeys(fn ($item) => [$item->id => $item->name])
            ->all();

        $functionalArea = FunctionalArea::query()
            ->wherePublished()
            ->orderBy('order')
            ->oldest('name')
            ->select('name', 'id')
            ->get()
            ->mapWithKeys(fn ($item) => [$item->id => $item->name])
            ->all();

        $tags = null;

        if ($model) {
            $tags = $model
                ->tags()
                ->select('name')
                ->get()
                ->mapWithKeys(fn ($item) => [$item->name => $item->name])
                ->implode(',');
        }

        $this
            ->setupModel(new Job())
            ->setValidatorClass(JobRequest::class)
            ->addCustomField('tags', TagField::class)
            ->addCustomField('multiCheckList', MultiCheckListField::class)
            ->addCustomField('tags', TagField::class)
            ->columns(12)
            ->add('name', 'text', [
                'label' => __('Job title'),
                'required' => true,
                'attr' => [
                    'placeholder' => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('description', TextareaField::class, DescriptionFieldOption::make())
            ->add(
                'is_featured',
                OnOffField::class,
                IsFeaturedFieldOption::make()
            )
            ->add('content', 'editor', [
                'label' => trans('core/base::forms.content'),
                'attr' => [
                    'rows' => 4,
                    'placeholder' => trans('core/base::forms.description_placeholder'),
                ],
            ])
            ->add('company_id', 'autocomplete', [
                'label' => __('Company'),
                'required' => true,
                'attr' => [
                    'id' => 'company_id',
                    'data-url' => route('companies.list'),
                ],
                'choices' => $model && $model->company_id
                    ? [$model->company->id => $model->company->name]
                    : ['' => __('Select company...')],
                'help_block' => [
                    'text' => sprintf(
                        '%s%s',
                        __('Not in the list? '),
                        Html::link('#', __('Add new'), ['data-bs-toggle' => 'modal', 'data-bs-target' => '#add-company-modal'])
                    ),
                    'attr' => ['class' => 'd-block mt-2'],
                ],
                'colspan' => 6,
            ])
            ->add('number_of_positions', 'number', [
                'label' => __('Number of positions'),
                'attr' => [
                    'placeholder' => __('Number of positions'),
                ],
                'default_value' => 1,
                'colspan' => 6,
            ])
            ->when(JobBoardHelper::isZipCodeEnabled(), function (FormAbstract $form): void {
                $form->add('zip_code', 'text', [
                    'label' => __('Zip code'),
                    'attr' => [
                        'placeholder' => __('Zip code'),
                        'data-counter' => 20,
                    ],
                    'colspan' => 6,
                ]);
            })
            ->add('address', 'text', [
                'label' => __('Address'),
                'attr' => [
                    'placeholder' => __('Address'),
                    'data-counter' => 120,
                ],
                'colspan' => JobBoardHelper::isZipCodeEnabled() ? 6 : 12,
            ])
            ->when(is_plugin_active('location'), function (FormAbstract $form): void {
                $form->add(
                    'location_data',
                    SelectLocationField::class,
                    SelectLocationFieldOption::make()
                );
            })
            ->when(JobBoardHelper::isEnabledLatLongFields(), function (FormAbstract $form): void {
                $form->add('latitude', 'text', [
                    'label' => __('Latitude'),
                    'attr' => [
                        'placeholder' => 'Ex: 1.462260',
                        'data-counter' => 25,
                    ],
                    'help_block' => [
                        'tag' => 'a',
                        'text' => __('Go here to get Latitude from address.'),
                        'attr' => [
                            'href' => 'https://www.latlong.net/convert-address-to-lat-long.html',
                            'target' => '_blank',
                            'rel' => 'nofollow',
                            'class' => 'small d-block mt-1',
                        ],
                    ],
                    'colspan' => 6,
                ])
                ->add('longitude', 'text', [
                    'label' => __('Longitude'),
                    'attr' => [
                        'placeholder' => 'Ex: 103.812530',
                        'data-counter' => 25,
                    ],
                    'help_block' => [
                        'tag' => 'a',
                        'text' => __('Go here to get Longitude from address.'),
                        'attr' => [
                            'href' => 'https://www.latlong.net/convert-address-to-lat-long.html',
                            'target' => '_blank',
                            'rel' => 'nofollow',
                            'class' => 'small d-block mt-1',
                        ],
                    ],
                    'colspan' => 6,
                ]);
            })
            ->add('salary_from', 'text', [
                'label' => __('Salary from'),
                'attr' => [
                    'id' => 'salary-from',
                    'placeholder' => __('Salary from'),
                    'class' => 'form-control input-mask-number',
                ],
                'colspan' => 3,
            ])
            ->add('salary_to', 'text', [
                'label' => __('Salary to'),
                'attr' => [
                    'id' => 'salary-to',
                    'placeholder' => __('Salary to'),
                    'class' => 'form-control input-mask-number',
                ],
                'colspan' => 3,
            ])
            ->add('salary_range', SelectField::class, [
                'label' => __('Salary Range'),
                'choices' => SalaryRangeEnum::labels(),
                'colspan' => 3,
            ])
            ->add('currency_id', SelectField::class, [
                'label' => __('Currency'),
                'choices' => $currencies,
                'colspan' => 3,
            ])
            ->add('hide_salary', OnOffField::class, [
                'label' => __('Hide salary?'),
                'default_value' => false,
            ])
            ->add('application_closing_date', DatePickerField::class, [
                'label' => __('Application closing date'),
                'value' => $model ? BaseHelper::formatDate($model->application_closing_date) : '',
                'colspan' => 6,
            ])
            ->add('apply_url', 'text', [
                'label' => __('URL to Apply Off-Site'),
                'attr' => [
                    'placeholder' => __('Ex: https://example.com'),
                    'data-counter' => 2048,
                ],
                'help_block' => [
                    'text' => __('Provide a URL if you prefer job seeker to apply directly on your website.'),
                ],
            ])
            ->add('hide_company', 'onOff', [
                'label' => __('Hide my company details (post anonymously)?'),
                'default_value' => false,
            ])
            ->add('never_expired', 'onOff', [
                'label' => __('Never expired?'),
                'default_value' => true,
                'help_block' => [
                    'text' => __('You can change Job Expired Time (days) in Admin → Settings → Job Board → General'),
                ],
            ])
            ->addOpenCollapsible('never_expired', '1', $this->model->never_expired ?? true)
            ->add('auto_renew', 'onOff', [
                'label' => __(
                    'Renew automatically (you will be charged again in :days days)?',
                    ['days' => JobBoardHelper::jobExpiredDays()]
                ),
                'default_value' => false,
                'help_block' => [
                    'text' => __('You need to set up a cronjob in Admin → Platform Administration → Cronjob first. Once configured, it will automatically renew jobs if the author has sufficient credits.'),
                ],
            ])
            ->addCloseCollapsible('never_expired', '1')
            ->add('status', SelectField::class, StatusFieldOption::make()->choices(JobStatusEnum::labels()))
            ->add('moderation_status', SelectField::class, [
                'label' => trans('plugins/job-board::job.moderation_status'),
                'choices' => ModerationStatusEnum::labels(),
            ])
            ->when(! JobBoardHelper::isUniqueIdFieldHiddenInAdminForm(), function (FormAbstract $form) {
                $form->add(
                    'unique_id',
                    TextField::class,
                    TextFieldOption::make()
                        ->value($this->getModel()->getKey() ? $this->getModel()->unique_id : $this->getModel()->generateUniqueId())
                        ->label(trans('plugins/job-board::job-board.form.unique_id'))
                        ->placeholder(trans('plugins/job-board::job-board.form.unique_id_placeholder', ['unique_id' => $this->getModel()->generateUniqueId(true)]))
                );
            })
            ->add('is_freelance', 'onOff', [
                'label' => __('Is freelance?'),
                'default_value' => false,
            ])
            ->add(
                'categories[]',
                TreeCategoryField::class,
                TreeCategoryFieldOption::make()
                    ->label(trans('plugins/job-board::job.categories'))
                    ->switchToDropdownThreshold(200)
                    ->choices(function () {
                        return Category::query()
                            ->wherePublished()
                            ->orderBy('order')
                            ->oldest('name')
                            ->select(['id', 'name', 'parent_id'])
                            ->with('activeChildren')
                            ->where('parent_id', 0)
                            ->get();
                    })
                    ->when($this->getModel()->getKey(), function (SelectFieldOption $fieldOption) {
                        /**
                         * @var Job $job
                         */
                        $job = $this->getModel();

                        return $fieldOption->selected($job->categories()->pluck('category_id')->all());
                    }, function (SelectFieldOption $fieldOption) {
                        return $fieldOption
                            ->selected(
                                Category::query()
                                    ->wherePublished()
                                    ->orderBy('order')
                                    ->oldest('name')
                                    ->where('is_default', 1)
                                    ->pluck('id')
                                    ->all()
                            );
                    })
            )
            ->when(! empty($skills), function (FormAbstract $form) use ($selectedSkills, $skills): void {
                $form->add('skills[]', 'multiCheckList', [
                    'label' => __('Job skills'),
                    'choices' => $skills,
                    'value' => old('skills', $selectedSkills),
                ]);
            })
            ->when(! empty($jobTypes), function (FormAbstract $form) use ($jobTypes, $selectedJobTypes): void {
                $form->add('jobTypes[]', 'multiCheckList', [
                    'label' => __('Job types'),
                    'choices' => $jobTypes,
                    'value' => old('jobTypes', $selectedJobTypes),
                ]);
            })
            ->add('career_level_id', SelectField::class, [
                'label' => __('Career level'),
                'choices' => [0 => __('-- select --')] + $careerLevels,
            ])
            ->add('functional_area_id', SelectField::class, [
                'label' => __('Functional area'),
                'choices' => [0 => __('-- select --')] + $functionalArea,
            ])
            ->add('degree_level_id', SelectField::class, [
                'label' => __('Degree level'),
                'choices' => [0 => __('-- select --')] + $degreeLevels,
            ])
            ->add('job_experience_id', SelectField::class, [
                'label' => __('Job experience'),
                'choices' => [0 => __('-- select --')] + $jobExperiences,
            ])
            ->add('tag', 'tags', [
                'label' => trans('plugins/job-board::job.tags'),
                'value' => $tags,
                'attr' => [
                    'placeholder' => trans('plugins/job-board::job.write_some_tags'),
                    'data-url' => route('job-board.tag.all'),
                ],
            ])
            ->setBreakFieldPoint('status')
            ->addMetaBoxes([
                'add-company' => [
                    'title' => null,
                    'content' => view('plugins/job-board::partials.add-company', ['model' => $model]),
                    'priority' => 0,
                    'attributes' => ['style' => 'display: none'],
                ],
                'colleagues' => [
                    'title' => __('Add colleagues'),
                    'subtitle' => __('We will notify your colleagues when someone applied.'),
                    'content' => view('plugins/job-board::partials.colleagues', ['model' => $model]),
                    'priority' => 0,
                ],
            ])
            ->when(JobBoardHelper::isEnabledCustomFields(), function (FormAbstract $form) use ($model): void {
                Assets::addScriptsDirectly('vendor/core/plugins/job-board/js/custom-fields.js');

                $customFields = CustomField::query()
                    ->orderBy('order')
                    ->oldest('name')
                    ->select(['name', 'id', 'type'])
                    ->get();

                $form->addMetaBoxes([
                    'custom_fields_box' => [
                        'title' => trans('plugins/job-board::custom-fields.name'),
                        'content' => view('plugins/job-board::custom-fields.custom-fields', [
                            'options' => CustomFieldEnum::labels(),
                            'customFields' => $customFields,
                            'model' => $model,
                            'ajax' => is_in_admin(true) ? route('job-board.custom-fields.get-info') : route(
                                'public.account.custom-fields.get-info'
                            ),
                        ]),
                        'priority' => 0,
                    ],
                ]);
            });
    }
}
