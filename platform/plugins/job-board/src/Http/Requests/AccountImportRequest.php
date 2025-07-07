<?php

namespace Botble\JobBoard\Http\Requests;

use Botble\JobBoard\Enums\AccountGenderEnum;
use Botble\JobBoard\Enums\AccountTypeEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class AccountImportRequest extends Request
{
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'max:120', 'min:2'],
            'last_name' => ['required', 'max:120', 'min:2'],
            'description' => ['nullable', 'string'],
            'gender' => ['nullable', Rule::in(AccountGenderEnum::toArray())],
            'email' => ['required', 'max:60', 'min:6', 'email', 'unique:jb_accounts'],
            'password' => ['nullable', 'min:6'],
            'dob' => ['nullable', 'date'],
            'phone' => ['nullable', 'max:20', 'min:6'],
            'address' => ['nullable', 'max:255'],
            'bio' => ['nullable', 'string'],
            'type' => ['required', 'string', Rule::in(AccountTypeEnum::toArray())],
            'resume' => ['nullable', 'string', 'url'],
            'cover_letter' => ['nullable', 'string', 'url'],
            'is_public_profile' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'available_for_hiring' => ['nullable', 'boolean'],
            'views' => ['nullable', 'int'],
            'country_id' => ['nullable', 'int'],
            'state_id' => ['nullable', 'int'],
            'city_id' => ['nullable', 'int'],
        ];
    }
}
