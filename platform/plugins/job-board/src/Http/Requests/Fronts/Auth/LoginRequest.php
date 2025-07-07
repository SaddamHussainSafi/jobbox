<?php

namespace Botble\JobBoard\Http\Requests\Fronts\Auth;

use Botble\Support\Http\Requests\Request;

class LoginRequest extends Request
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }
}
