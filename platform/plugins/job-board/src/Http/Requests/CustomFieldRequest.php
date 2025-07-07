<?php

namespace Botble\JobBoard\Http\Requests;

use Botble\JobBoard\Enums\CustomFieldEnum;
use Botble\JobBoard\Models\CustomField;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomFieldRequest extends FormRequest
{
    public function rules(): array
    {
        $isDropdownField = $this->input('type') === CustomFieldEnum::DROPDOWN;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(CustomField::class)->ignore($this->custom_field, 'id'),
            ],
            'type' => ['required', 'string'],
            'is_global' => ['required', 'boolean'],
            'options.*.id' => [
                'sometimes',
            ],
            'options.*.label' => [
                Rule::requiredIf(fn () => $isDropdownField),
            ],
            'options.*.value' => [
                Rule::requiredIf(fn () => $isDropdownField),
            ],
            'options.*.order' => [
                'numeric',
                'min:0',
                'max:999',
            ],
        ];
    }
}
