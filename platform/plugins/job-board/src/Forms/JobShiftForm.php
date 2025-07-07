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
use Botble\JobBoard\Http\Requests\JobShiftRequest;
use Botble\JobBoard\Models\JobShift;

class JobShiftForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->setupModel(new JobShift())
            ->setValidatorClass(JobShiftRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->required())
            ->add('order', NumberField::class, SortOrderFieldOption::make())
            ->add('is_default', OnOffField::class, IsDefaultFieldOption::make())
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->setBreakFieldPoint('status');
    }
}
