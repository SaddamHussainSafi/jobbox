<?php

namespace Botble\JobBoard\Http\Requests;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Supports\Language;
use Botble\JobBoard\Enums\AccountGenderEnum;
use Botble\JobBoard\Enums\AccountTypeEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class SettingRequest extends Request
{
    public function rules(): array
    {
        $rules = [
            'first_name' => 'required|max:120|min:2',
            'last_name' => 'required|max:120|min:2',
            'phone' => 'nullable|' . BaseHelper::getPhoneValidationRule(),
            'dob' => 'nullable|date',
            'address' => 'nullable|max:250',
            'gender' => 'nullable|' . Rule::in(AccountGenderEnum::values()),
            'description' => 'nullable|max:4000',
            'bio' => 'nullable',
            'country_id' => 'nullable|numeric',
            'state_id' => 'nullable|numeric',
            'city_id' => 'nullable|numeric',
            'locale' => ['sometimes', 'required', Rule::in(array_keys(Language::getAvailableLocales()))],
        ];

        $account = auth('account')->user();
        if (! $account->type->getKey()) {
            $rules['type'] = Rule::in(AccountTypeEnum::values());
        }

        if ($account->isJobSeeker()) {
            $rules = array_merge($rules, [
                'is_public_profile' => Rule::in([0, 1]),
                'hide_cv' => Rule::in([0, 1]),
                'available_for_hiring' => Rule::in([0, 1]),
                'resume' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx',
                'cover_letter' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx',
            ]);
        }

        return $rules;
    }
}
