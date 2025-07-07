<?php

namespace Botble\JobBoard\Http\Requests\Fronts\Auth;

use Botble\Base\Facades\BaseHelper;
use Botble\Support\Http\Requests\Request;

class RegisterRequest extends Request
{
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'max:120', 'min:2'],
            'last_name' => ['required', 'max:120', 'min:2'],
            'email' => ['required', 'max:60', 'min:6', 'email', 'unique:jb_accounts'],
            'phone' => 'nullable|' . BaseHelper::getPhoneValidationRule(),
            'password' => ['required', 'min:6', 'confirmed'],
            'agree_terms_and_policy' => ['sometimes', 'accepted:1'],
        ];
    }
}
