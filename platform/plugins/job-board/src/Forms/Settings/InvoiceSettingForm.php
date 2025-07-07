<?php

namespace Botble\JobBoard\Forms\Settings;

use Botble\Base\Forms\FieldOptions\RadioFieldOption;
use Botble\Base\Forms\Fields\GoogleFontsField;
use Botble\Base\Forms\Fields\RadioField;
use Botble\JobBoard\Http\Requests\Settings\InvoiceSettingRequest;
use Botble\JobBoard\Supports\InvoiceHelper;
use Botble\Setting\Forms\SettingForm;

class InvoiceSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setSectionTitle(trans('plugins/job-board::settings.invoice.title'))
            ->setSectionDescription(trans('plugins/job-board::settings.invoice.description'))
            ->addCustomField('googleFonts', GoogleFontsField::class)
            ->setValidatorClass(InvoiceSettingRequest::class)
            ->add('job_board_company_name_for_invoicing', 'text', [
                'label' => trans('plugins/job-board::settings.invoice.company_name'),
                'value' => setting('job_board_company_name_for_invoicing', theme_option('site_title')),
            ])
            ->add('job_board_company_address_for_invoicing', 'text', [
                'label' => trans('plugins/job-board::settings.invoice.company_address'),
                'value' => setting('job_board_company_address_for_invoicing', theme_option('site_title')),
            ])
            ->add('job_board_company_email_for_invoicing', 'text', [
                'label' => trans('plugins/job-board::settings.invoice.company_email'),
                'value' => setting('job_board_company_email_for_invoicing', get_admin_email()->first()),
            ])
            ->add('job_board_company_phone_for_invoicing', 'text', [
                'label' => trans('plugins/job-board::settings.invoice.company_phone'),
                'value' => setting('job_board_company_phone_for_invoicing'),
            ])
            ->add('job_board_company_logo_for_invoicing', 'mediaImage', [
                'label' => trans('plugins/job-board::settings.invoice.company_logo'),
                'value' => setting('job_board_company_logo_for_invoicing') ?: theme_option('logo'),
            ])
            ->add('job_board_using_custom_font_for_invoice', 'onOffCheckbox', [
                'label' => trans('plugins/job-board::settings.invoice.using_custom_font_for_invoice'),
                'value' => setting('job_board_using_custom_font_for_invoice', false),
                'attr' => [
                    'data-bb-toggle' => 'collapse',
                    'data-bb-target' => '.custom-font-settings',
                ],
            ])
            ->add('open_fieldset_custom_font_settings', 'html', [
                'html' => sprintf(
                    '<fieldset class="form-fieldset custom-font-settings" style="display: %s;" data-bb-value="1">',
                    old('job_board_using_custom_font_for_invoice', setting('job_board_using_custom_font_for_invoice', false)) ? 'block' : 'none'
                ),
            ])
            ->add('job_board_invoice_font_family', 'googleFonts', [
                'label' => trans('plugins/job-board::settings.invoice.invoice_font_family'),
                'selected' => setting('job_board_invoice_font_family'),
            ])
            ->add('close_fieldset_custom_font_settings', 'html', [
                'html' => '</fieldset>',
            ])
            ->add('job_board_invoice_support_arabic_language', 'onOffCheckbox', [
                'label' => trans('plugins/job-board::settings.invoice.invoice_support_arabic_language'),
                'value' => setting('job_board_invoice_support_arabic_language', false),
            ])
            ->add('job_board_enable_invoice_stamp', 'onOffCheckbox', [
                'label' => trans('plugins/job-board::settings.invoice.enable_invoice_stamp'),
                'value' => setting('job_board_enable_invoice_stamp', true),
            ])
            ->add('job_board_invoice_code_prefix', 'text', [
                'label' => trans('plugins/job-board::settings.invoice.invoice_code_prefix'),
                'value' => setting('job_board_invoice_code_prefix', 'INV-'),
            ])
            ->add(
                'invoice_language_support',
                RadioField::class,
                RadioFieldOption::make()
                    ->label(trans('plugins/job-board::settings.invoice.add_language_support'))
                    ->choices([
                        'default' => trans('plugins/job-board::settings.invoice.only_latin_languages'),
                        'arabic' => 'Arabic',
                        'bangladesh' => 'Bangladesh',
                        'chinese' => 'Chinese',
                    ])
                    ->defaultValue('default')
                    ->when(
                        (new InvoiceHelper())->getLanguageSupport(),
                        function (RadioFieldOption $option, string $language): void {
                            $option->selected($language);
                        }
                    )
                    ->colspan(6)
            );
    }
}
