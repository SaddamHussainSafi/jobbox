<?php

namespace Botble\JobBoard\Http\Requests;

use Botble\Support\Http\Requests\Request;

class AccountEducationRequest extends Request
{
    public function rules(): array
    {
        return [
            'school' => ['required', 'string'],
            'specialized' => ['nullable', 'string'],
            'description' => ['nullable'],
            'started_at' => ['required', 'date'],
            'ended_at' => ['nullable', 'date'],
        ];
    }
}
