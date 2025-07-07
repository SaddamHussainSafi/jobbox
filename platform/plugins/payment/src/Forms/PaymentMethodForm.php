<?php

namespace Botble\Payment\Forms;

use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\FormAbstract;
use Botble\Payment\Enums\PaymentFeeTypeEnum;
use Illuminate\Support\HtmlString;

class PaymentMethodForm extends FormAbstract
{
    public function setup(): void
    {
        $this->template('plugins/payment::forms.payment-method');
    }

    protected function paymentId(string $id): static
    {
        $this->setFormOption('payment_id', $id);

        return $this;
    }

    protected function paymentName(string $name): static
    {
        $this->setFormOption('payment_name', $name);

        return $this;
    }

    protected function paymentDescription(string $description): static
    {
        $this->setFormOption('payment_description', $description);

        return $this;
    }

    protected function paymentLogo(string $logo): static
    {
        $this->setFormOption('payment_logo', $logo);

        return $this;
    }

    protected function paymentUrl(string $url): static
    {
        $this->setFormOption('payment_url', $url);

        return $this;
    }

    protected function paymentInstructions(string $paymentInstructions): static
    {
        $this->setFormOption('payment_instructions', $paymentInstructions);

        return $this;
    }

    protected function defaultDescriptionValue(string $value): static
    {
        $this->setFormOption('default_description_value', $value);

        return $this;
    }

    protected function paymentMethodLogoField(string $name): static
    {
        return $this
            ->add(
                get_payment_setting_key('logo', $name),
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(trans('plugins/payment::payment.method_logo'))
                    ->value(get_payment_setting('logo', $name))
            );
    }

    protected function paymentFeeField(string $name): static
    {
        $feeType = get_payment_setting('fee_type', $name, PaymentFeeTypeEnum::FIXED);

        $label = trans('plugins/payment::payment.processing_fee');

        if ($feeType === PaymentFeeTypeEnum::PERCENTAGE) {
            $label = trans('plugins/payment::payment.fee') . ' (%)';
        }

        return $this
            ->add(
                get_payment_setting_key('fee', $name),
                'text',
                [
                    'label' => $label,
                    'value' => get_payment_setting('fee', $name, 0),
                    'help_block' => [
                        'text' => trans('plugins/payment::payment.fee_helper'),
                    ],
                    'attr' => [
                        'placeholder' => '0',
                        'class' => 'form-control input-mask-number',
                        'data-thousands-separator' => ',',
                        'data-decimal-separator' => '.',
                    ],
                ]
            )
            ->add(
                get_payment_setting_key('fee_type', $name),
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/payment::payment.fee_type'))
                    ->choices(PaymentFeeTypeEnum::labels())
                    ->selected(get_payment_setting('fee_type', $name, PaymentFeeTypeEnum::FIXED))
                    ->helperText(trans('plugins/payment::payment.fee_type_helper', ['currency' => get_application_currency()->title]))
            );
    }

    public function getPaymentInstructions(): HtmlString
    {
        return new HtmlString($this->getFormOption('payment_instructions'));
    }
}
