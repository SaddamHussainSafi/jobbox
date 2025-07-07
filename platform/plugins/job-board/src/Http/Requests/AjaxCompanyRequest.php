<?php

namespace Botble\JobBoard\Http\Requests;

use Botble\Support\Http\Requests\Request;

class AjaxCompanyRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
        ];
    }
}
