<?php

namespace Botble\JobBoard\Http\Requests;

use Botble\Support\Http\Requests\Request;

class AccountCompanyRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'description' => ['nullable', 'max:400'],
            'website' => ['nullable', 'max:120'],
            'address' => ['nullable', 'max:250'],
            'postal_code' => ['nullable', 'max:20'],
            'phone' => ['nullable', 'max:25'],
            'year_founded' => ['nullable', 'max:4'],
            'ceo' => ['nullable', 'max:120'],
            'number_of_offices' => ['nullable', 'numeric'],
            'number_of_employees' => ['nullable', 'numeric'],
            'annual_revenue' => ['nullable', 'max:60'],
            'logo_input' => ['nullable', 'file', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'cover_image_input' => ['nullable', 'file', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'facebook' => ['nullable', 'max:200'],
            'twitter' => ['nullable', 'max:200'],
            'linkedin' => ['nullable', 'max:200'],
            'instagram' => ['nullable', 'max:200'],
            'latitude' => ['max:20', 'nullable', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'longitude' => [
                'max:20',
                'nullable',
                'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/',
            ],
        ];
    }
}
