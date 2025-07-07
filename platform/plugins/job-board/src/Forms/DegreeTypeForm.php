<?php

namespace Botble\JobBoard\Forms;

use Botble\Base\Forms\FieldOptions\IsDefaultFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\SortOrderFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\JobBoard\Http\Requests\DegreeTypeRequest;
use Botble\JobBoard\Models\DegreeLevel;
use Botble\JobBoard\Models\DegreeType;

class DegreeTypeForm extends FormAbstract
{
    public function setup(): void
    {
        $degreeLevels = DegreeLevel::query()->pluck('name', 'id')->all();

        $this
            ->setupModel(new DegreeType())
            ->setValidatorClass(DegreeTypeRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->required())
            ->add('degree_level_id', 'customSelect', [
                'label' => trans('plugins/job-board::degree-type.degree-level'),
                'required' => true,
                'attr' => [
                    'class' => 'form-control select-search-full',
                ],
                'choices' => $degreeLevels,
            ])
            ->add('order', NumberField::class, SortOrderFieldOption::make())
            ->add('is_default', OnOffField::class, IsDefaultFieldOption::make())
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->setBreakFieldPoint('status');
    }
}
