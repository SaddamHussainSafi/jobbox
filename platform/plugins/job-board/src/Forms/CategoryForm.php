<?php

namespace Botble\JobBoard\Forms;

use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\IsFeaturedFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\JobBoard\Http\Requests\CategoryRequest;
use Botble\JobBoard\Models\Category;

class CategoryForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->setupModel(new Category())
            ->setValidatorClass(CategoryRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->required())
            ->add('description', TextareaField::class, DescriptionFieldOption::make()->maxLength(1200))
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->add(
                'is_featured',
                OnOffField::class,
                IsFeaturedFieldOption::make()
            )
            ->setBreakFieldPoint('status');
    }
}
