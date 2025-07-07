<?php

namespace Botble\Shortcode\Forms;

use Botble\Base\Forms\FieldOptions\ColorFieldOption;
use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\Fields\ColorField;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\FormAbstract;
use Botble\Base\Models\BaseModel;
use Botble\Shortcode\Forms\Fields\ShortcodeTabsField;

class ShortcodeForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(BaseModel::class)
            ->contentOnly()
            ->addCustomField('tabs', ShortcodeTabsField::class);
    }

    public function renderForm(array $options = [], bool $showStart = false, bool $showFields = true, bool $showEnd = false): string
    {
        return parent::renderForm($options, $showStart, $showFields, $showEnd);
    }

    public function withLazyLoading(bool $lazy = true): static
    {
        self::beforeRendering(function (self $form) use ($lazy) {
            if (! $lazy) {
                $form->remove('enable_lazy_loading');

                return $this;
            }

            $form->add(
                'enable_lazy_loading',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(__('Enable lazy loading'))
                    ->choices([
                        'no' => __('No'),
                        'yes' => __('Yes'),
                    ])
                    ->helperText(__('When enabled, shortcode content will be loaded sequentially as the page loads, rather than all at once. This can help improve page load times.'))
            );

            return $this;
        });

        return $this;
    }

    public function withHtmlAttributes(string $defaultBackgroundColor = '#fff', string $defaultColor = null): static
    {
        return $this
            ->withBackgroundColor($defaultBackgroundColor)
            ->withBackgroundImage()
            ->withTextColor($defaultColor)
            ->withCustomCSS();
    }

    public function withCustomCSS(): static
    {
        return $this->add(
            'custom_css',
            TextareaField::class,
            TextareaFieldOption::make()
                ->label(__('Custom CSS (optional)'))
                ->helperText(__('Please enter your CSS code on a single line. It wont work if it has break line. Some special characters may be escaped.'))
        );
    }

    public function withBackgroundColor(string $defaultColor = '#fff'): static
    {
        return $this
                ->add(
                    'background_color',
                    ColorField::class,
                    ColorFieldOption::make()
                        ->label(__('Background color (optional)'))
                        ->when($defaultColor, fn (ColorFieldOption $option) => $option->defaultValue($defaultColor))
                );
    }

    public function withTextColor(string $defaultColor = null): static
    {
        return $this
            ->add(
                'text_color',
                ColorField::class,
                ColorFieldOption::make()
                    ->label(__('Text color (optional)'))
                    ->when($defaultColor, fn (ColorFieldOption $option) => $option->defaultValue($defaultColor))
                    ->helperText(__('This color may be overridden by the theme. If it doesnt work, please add your CSS in Appearance -> Custom CSS.'))
            );
    }

    public function withBackgroundImage(string $defaultImage = null): static
    {
        return $this
            ->add(
                'background_image',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('Background image (optional)'))
                    ->when($defaultImage, fn (MediaImageFieldOption $option) => $option->defaultValue($defaultImage))
            );
    }
}
