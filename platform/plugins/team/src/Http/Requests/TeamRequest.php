<?php

namespace Botble\Team\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Facades\BaseHelper;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class TeamRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:400'],
            'content' => ['nullable', 'string', 'max:10000'],
            'title' => ['nullable', 'string', 'max:255'],
            'phone' => 'nullable|' . BaseHelper::getPhoneValidationRule(),
            'email' => ['nullable', 'email', 'min:6', 'max:60'],
            'address' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'photo' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'socials' => ['nullable', 'array'],
            'socials.*' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', Rule::in(BaseStatusEnum::values())],
        ];
    }
}
