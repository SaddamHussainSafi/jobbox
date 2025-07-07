<?php

namespace Botble\JobBoard\Http\Requests;

use Botble\Base\Supports\Language;
use Botble\JobBoard\Models\AccountLanguage;
use Botble\JobBoard\Models\LanguageLevel;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class AccountLanguageRequest extends Request
{
    public function rules(): array
    {
        return [
            'language' => [
                'required',
                'string',
                Rule::in(array_keys(Language::getLocales())),
                Rule::unique(AccountLanguage::class, 'language')
                    ->ignore($this->route('language'))
                    ->where('account_id', $this->input('account') ?: auth('account')->id()),
            ],
            'language_level_id' => ['required', 'string', Rule::exists(LanguageLevel::class, 'id')],
            'is_native' => ['sometimes', 'bool'],
        ];
    }
}
