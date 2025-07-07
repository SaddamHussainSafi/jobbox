<?php

namespace Botble\JobBoard\Forms;

use Botble\Base\Facades\Assets;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\JobBoard\Enums\CustomFieldEnum;
use Botble\JobBoard\Http\Requests\CustomFieldRequest;
use Botble\JobBoard\Models\CustomField;

class CustomFieldForm extends FormAbstract
{
    public function setup(): void
    {
        Assets::addScripts(['jquery-ui'])
            ->addScriptsDirectly([
                'vendor/core/plugins/job-board/js/global-custom-fields.js',
            ]);

        $this
            ->setupModel(new CustomField())
            ->setValidatorClass(CustomFieldRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->required())
            ->add('type', 'customSelect', [
                'label' => trans('plugins/job-board::custom-fields.type'),
                'required' => true,
                'attr' => ['class' => 'form-control custom-field-type'],
                'choices' => CustomFieldEnum::labels(),
            ])
            ->setBreakFieldPoint('type')
            ->addMetaBoxes([
                'custom_fields_box' => [
                    'attributes' => [
                        'id' => 'custom_fields_box',
                    ],
                    'id' => 'custom_fields_box',
                    'title' => trans('plugins/job-board::custom-fields.options'),
                    'content' => view(
                        'plugins/job-board::custom-fields.options',
                        ['options' => $this->model->options->sortBy('order')]
                    )->render(),
                    'header_actions' => view('plugins/job-board::custom-fields.header-actions-button', [
                        'id' => 'add-new-row',
                        'label' => trans('plugins/job-board::custom-fields.option.add_row'),
                    ])->render(),
                    'has_table' => true,
                ],
            ]);
    }
}
