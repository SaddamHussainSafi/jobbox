<?php

namespace Botble\JobBoard\Forms\Fronts\Auth\Concerns;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Forms\FieldOptions\HtmlFieldOption;
use Botble\Base\Forms\Fields\HtmlField;

trait HasSubmitButton
{
    public function submitButton(
        string $label,
        ?string $icon = null,
        string $iconPosition = 'append',
        bool $isWrapped = true,
        string $wrapperClass = 'd-grid',
        array $attributes = []
    ): static {
        $icon = $icon ? BaseHelper::renderIcon($icon) : '';
        $label = $icon ? ($iconPosition === 'prepend' ? $icon . ' ' . $label : $label . ' ' . $icon) : $label;

        return $this
            ->when(
                $isWrapped,
                fn ($form)
                    => $form->add(
                        'openButtonWrap',
                        HtmlField::class,
                        HtmlFieldOption::make()
                            ->content(sprintf('<div class="%s">', $wrapperClass))
                    )
            )
            ->add('submit', 'submit', [
                'label' => $label,
                'attr' => [
                    'class' => 'btn btn-primary btn-auth-submit',
                    ...$attributes,
                ],
            ])
            ->when(
                $isWrapped,
                fn ($form)
                    => $form->add(
                        'closeButtonWrap',
                        HtmlField::class,
                        HtmlFieldOption::make()
                            ->content('</div>')
                    )
            );
    }

}
