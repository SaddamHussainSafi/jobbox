<?php

use Botble\Base\Forms\FieldOptions\ButtonFieldOption;
use Botble\Base\Forms\FieldOptions\EmailFieldOption;
use Botble\Base\Forms\FieldOptions\HtmlFieldOption;
use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\EmailField;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Newsletter\Forms\Fronts\NewsletterForm;
use Botble\Widget\AbstractWidget;
use Botble\Widget\Forms\WidgetForm;

class NewsletterWidget extends AbstractWidget
{
    public function __construct()
    {
        parent::__construct([
            'name' => __('Newsletter form'),
            'description' => __('Display Newsletter form footer'),
            'title' => null,
            'background_image' => null,
            'image_left' => null,
            'image_right' => null,
        ]);
    }

    public function settingForm(): ?WidgetForm
    {
        return WidgetForm::createFromArray($this->getConfig())
            ->add(
                'title',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Title'))
                    ->toArray()
            )
            ->add(
                'background_image',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('Background image'))
                    ->toArray()
            )
            ->add(
                'image_left',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('Image left'))
                    ->toArray()
            )
            ->add(
                'image_right',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('Image right'))
                    ->toArray()
            );
    }

    public function data(): array
    {
        if (! is_plugin_active('newsletter')) {
            return [];
        }

        $form = NewsletterForm::create()
            ->formClass('form-newsletter subscribe-form newsletter-form')
            ->remove(['submit', 'wrapper_before', 'email'])
            ->addBefore(
                'wrapper_after',
                'email',
                EmailField::class,
                EmailFieldOption::make()
                    ->label(false)
                    ->cssClass('input-newsletter')
                    ->wrapperAttributes(false)
                    ->maxLength(-1)
                    ->placeholder(__('Enter Your Email'))
                    ->toArray()
            )
            ->addBefore(
                'email',
                'wrapper_before',
                HtmlField::class,
                HtmlFieldOption::make()->content('<div class="input-group d-flex">')->toArray()
            )
            ->addAfter(
                'email',
                'submit',
                'submit',
                ButtonFieldOption::make()
                    ->label('Subscribe')
                    ->attributes(['class' => 'btn btn-default font-heading icon-send-letter'])
                    ->toArray(),
            );

        return compact('form');
    }
}
