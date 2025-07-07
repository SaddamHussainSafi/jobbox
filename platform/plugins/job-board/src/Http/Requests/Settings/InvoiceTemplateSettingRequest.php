<?php

namespace Botble\JobBoard\Http\Requests\Settings;

use Botble\Support\Http\Requests\Request;

class InvoiceTemplateSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'max:1000000'],
        ];
    }
}
