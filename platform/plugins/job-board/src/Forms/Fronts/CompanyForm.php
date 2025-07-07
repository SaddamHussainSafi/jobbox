<?php

namespace Botble\JobBoard\Forms\Fronts;

use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\FormAbstract;
use Botble\JobBoard\Facades\JobBoardHelper;
use Botble\JobBoard\Forms\Fields\CustomEditorField;
use Botble\JobBoard\Http\Requests\CompanyRequest;
use Botble\JobBoard\Models\Company;
use Botble\Location\Fields\Options\SelectLocationFieldOption;
use Botble\Location\Fields\SelectLocationField;

class CompanyForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->setupModel(new Company())
            ->setValidatorClass(CompanyRequest::class)
            ->columns(12)
            ->setFormOption('enctype', 'multipart/form-data')
            ->template(JobBoardHelper::viewPath('dashboard.forms.base'))
            ->add('name', 'text', [
                'label' => __('Company Name'),
                'required' => true,
                'attr' => [
                    'placeholder' => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('description', TextareaField::class, DescriptionFieldOption::make())
            ->add('content', CustomEditorField::class, [
                'label' => trans('core/base::forms.content'),
                'attr' => [
                    'rows' => 4,
                    'placeholder' => trans('core/base::forms.description_placeholder'),
                ],
            ])
            ->add('tax_id', 'text', [
                'label' => __('Tax ID'),
                'attr' => [
                    'placeholder' => __('Tax ID'),
                    'data-counter' => 60,
                ],
                'colspan' => 4,
            ])
            ->add('ceo', 'text', [
                'label' => __('Company CEO / President'),
                'attr' => [
                    'placeholder' => __('Company CEO / President'),
                    'data-counter' => 120,
                ],
                'colspan' => 4,
            ])
            ->add('email', 'email', [
                'label' => __('Email'),
                'attr' => [
                    'placeholder' => __('Ex: contact@your-company.com'),
                    'data-counter' => 120,
                ],
                'colspan' => 4,
            ])
            ->add('phone', 'text', [
                'label' => __('Phone'),
                'attr' => [
                    'placeholder' => __('Phone number, will be public'),
                    'data-counter' => 30,
                ],
                'colspan' => 4,
            ])
            ->add('website', 'text', [
                'label' => __('Website'),
                'attr' => [
                    'placeholder' => __('https://'),
                    'data-counter' => 120,
                ],
                'colspan' => 4,
            ])
            ->add('year_founded', 'number', [
                'label' => __('Year founded'),
                'attr' => [
                    'placeholder' => __('Ex: 1987'),
                    'data-counter' => 10,
                ],
                'colspan' => 4,
            ])
            ->add('number_of_offices', 'number', [
                'label' => __('Number of offices'),
                'attr' => [
                    'placeholder' => __('Ex: 3'),
                    'data-counter' => 10,
                ],
                'colspan' => 4,
            ])
            ->add('number_of_employees', 'number', [
                'label' => __('Number of employees'),
                'attr' => [
                    'placeholder' => __('Ex: 100-250'),
                    'data-counter' => 10,
                ],
                'colspan' => 4,
            ])
            ->add('annual_revenue', 'text', [
                'label' => __('Annual revenue'),
                'attr' => [
                    'placeholder' => __('Ex: 2M'),
                    'data-counter' => 10,
                ],
                'colspan' => 4,
            ])
            ->when(is_plugin_active('location'), function (FormAbstract $form): void {
                $form->add(
                    'location_data',
                    SelectLocationField::class,
                    SelectLocationFieldOption::make()
                );
            })
            ->add('address', 'text', [
                'label' => __('Address'),
                'attr' => [
                    'placeholder' => __('Address'),
                    'data-counter' => 120,
                ],
                'colspan' => 6,
            ])
            ->add('postal_code', 'text', [
                'label' => __('Postal code'),
                'attr' => [
                    'placeholder' => __('Postal code'),
                    'data-counter' => 20,
                ],
                'colspan' => 6,
            ])
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
                            'class' => 'd-block mt-1 small',
                        ],
                    ],
                    'colspan' => 6,
                ]);
            })
            ->when(JobBoardHelper::isEnabledLatLongFields(), function (FormAbstract $form): void {
                $form->add('longitude', 'text', [
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
                            'class' => 'd-block mt-1 small',
                        ],
                    ],
                    'colspan' => 6,
                ]);
            })
            ->add('logo', 'mediaImage', [
                'label' => __('Logo (~500x500)'),
            ])
            ->add('cover_image', 'mediaImage', [
                'label' => __('Cover Image (~1920x300)'),
            ])
            ->setBreakFieldPoint('logo')
            ->addMetaBoxes([
                'social_links' => [
                    'title' => __('Social links'),
                    'content' => view(
                        JobBoardHelper::viewPath('dashboard.forms.social-links'),
                        ['company' => $this->getModel()]
                    )->render(),
                ],
            ]);
    }
}
