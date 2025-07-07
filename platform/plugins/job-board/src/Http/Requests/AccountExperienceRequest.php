<?php

namespace Botble\JobBoard\Http\Requests;

use Botble\Support\Http\Requests\Request;

class AccountExperienceRequest extends Request
{
    public function rules(): array
    {
        return [
            'company' => ['required', 'string'],
            'position' => ['nullable', 'string'],
            'description' => ['nullable'],
            'started_at' => ['required', 'date'],
            'ended_at' => ['nullable', 'date'],
        ];
    }
}
