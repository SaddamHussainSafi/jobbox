<?php

namespace Botble\JobBoard\Http\Requests\Settings;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Rules\GoogleFontsRule;
use Botble\Base\Rules\OnOffRule;
use Botble\Support\Http\Requests\Request;

class InvoiceSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'job_board_company_name_for_invoicing' => ['nullable', 'string', 'max:120'],
            'job_board_company_address_for_invoicing' => ['nullable', 'string', 'max:255'],
            'job_board_company_email_for_invoicing' => ['nullable', 'email'],
            'job_board_company_phone_for_invoicing' => 'nullable|' . BaseHelper::getPhoneValidationRule(),
            'job_board_company_logo_for_invoicing' => ['nullable', 'string', 'max:255'],
            'job_board_using_custom_font_for_invoice' => [new OnOffRule()],
            'job_board_invoice_font_family' => ['nullable', 'required_if:job_board_using_custom_font_for_invoice,1', 'string', new GoogleFontsRule()],
            'job_board_invoice_support_arabic_language' => [new OnOffRule()],
            'job_board_enable_invoice_stamp' => [new OnOffRule()],
            'job_board_invoice_code_prefix' => ['nullable', 'string', 'max:120'],
        ];
    }
}
