<?php

namespace Botble\JobBoard\Forms;

use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\JobBoard\Http\Requests\TagRequest;
use Botble\JobBoard\Models\Tag;

class TagForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->setupModel(new Tag())
            ->setValidatorClass(TagRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->required())
            ->add('description', TextareaField::class, DescriptionFieldOption::make())
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->setBreakFieldPoint('status');
    }
}
