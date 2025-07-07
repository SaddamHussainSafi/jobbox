<?php

namespace Botble\JobBoard\Forms;

use Botble\Base\Facades\Assets;
use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\IsDefaultFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\SortOrderFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\RepeaterField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\JobBoard\Http\Requests\PackageRequest;
use Botble\JobBoard\Models\Currency;
use Botble\JobBoard\Models\Package;

class PackageForm extends FormAbstract
{
    public function setup(): void
    {
        Assets::addScripts(['input-mask']);

        $currencies = Currency::query()->pluck('title', 'id')->all();

        $this
            ->setupModel(new Package())
            ->setValidatorClass(PackageRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->required())
            ->add('description', TextareaField::class, DescriptionFieldOption::make())
            ->add('rowOpen1', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('price', 'text', [
                'label' => trans('plugins/job-board::package.price'),
                'wrapper' => [
                    'class' => 'form-group col-md-6',
                ],
                'attr' => [
                    'id' => 'price-number',
                    'placeholder' => trans('plugins/job-board::package.price'),
                    'class' => 'form-control input-mask-number',
                ],
            ])
            ->add('currency_id', 'customSelect', [
                'label' => trans('plugins/job-board::package.currency'),
                'wrapper' => [
                    'class' => 'form-group col-md-6',
                ],
                'choices' => $currencies,
            ])
            ->add('rowClose1', 'html', [
                'html' => '</div>',
            ])
            ->add('rowOpen2', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('percent_save', 'text', [
                'label' => trans('plugins/job-board::package.percent_save'),
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
                'attr' => [
                    'id' => 'percent-save-number',
                    'placeholder' => trans('plugins/job-board::package.percent_save'),
                    'class' => 'form-control input-mask-number',
                ],
            ])
            ->add('number_of_listings', 'text', [
                'label' => trans('plugins/job-board::package.number_of_listings'),
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
                'attr' => [
                    'id' => 'price-number',
                    'placeholder' => trans('plugins/job-board::package.number_of_listings'),
                    'class' => 'form-control input-mask-number',
                ],
            ])
            ->add('account_limit', 'text', [
                'label' => trans('plugins/job-board::package.account_limit'),
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
                'attr' => [
                    'id' => 'percent-save-number',
                    'placeholder' => trans('plugins/job-board::package.account_limit_placeholder'),
                    'class' => 'form-control input-mask-number',
                ],
            ])
            ->add('rowClose2', 'html', [
                'html' => '</div>',
            ])
            ->add('is_default', OnOffField::class, IsDefaultFieldOption::make())
            ->add('features', RepeaterField::class, [
                'label' => trans('plugins/job-board::package.features'),
                'fields' => [
                    [
                        'type' => 'text',
                        'label' => trans('plugins/job-board::package.title'),
                        'attributes' => [
                            'name' => 'text',
                            'value' => null,
                            'options' => [
                                'class' => 'form-control',
                                'data-counter' => 255,
                                'placeholder' => trans('plugins/job-board::package.feature_helper_text'),
                            ],
                        ],
                    ],
                ],
            ])
            ->add('order', NumberField::class, SortOrderFieldOption::make())
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->setBreakFieldPoint('status');
    }
}
