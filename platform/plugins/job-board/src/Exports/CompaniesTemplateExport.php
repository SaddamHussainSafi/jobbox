<?php

namespace Botble\JobBoard\Exports;

use Botble\Base\Enums\BaseStatusEnum;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CompaniesTemplateExport implements FromCollection, WithHeadings
{
    use Exportable;

    public function collection(): Collection
    {
        $yesNo = ['Yes', 'No'];

        $data = [
            [
                'name' => 'Facebook',
                'description' => 'Saepe ut suscipit provident dolor. Fugiat aspernatur rem distinctio vitae et. Quo ut quos rerum. Itaque tempore animi possimus et consectetur voluptate perferendis ipsam.',
            ],
            [
                'name' => 'Twitter',
                'description' => 'Saepe ut suscipit provident dolor. Fugiat aspernatur rem distinctio vitae et. Quo ut quos rerum. Itaque tempore animi possimus et consectetur voluptate perferendis ipsam.',
            ],
            [
                'name' => 'Google',
                'description' => 'Saepe ut suscipit provident dolor. Fugiat aspernatur rem distinctio vitae et. Quo ut quos rerum. Itaque tempore animi possimus et consectetur voluptate perferendis ipsam.',
            ],
            [
                'name' => 'Apple',
                'description' => 'Saepe ut suscipit provident dolor. Fugiat aspernatur rem distinctio vitae et. Quo ut quos rerum. Itaque tempore animi possimus et consectetur voluptate perferendis ipsam.',
            ],
        ];

        $companies = [];

        foreach ($data as $item) {
            $companies[] = array_merge($item, [
                'slug' => Str::slug($item['name']),
                'email' => 'email@example.com',
                'content' => 'Content',
                'account_manager' => 'Steven,Josiane',
                'website' => 'https://example.com',
                'logo' => 'companies/logo.png',
                'latitude' => '43.880968',
                'longitude' => '-75.02625',
                'address' => '75249 Little Station New Freemanstad, CT 22054-3669',
                'country' => null,
                'state' => null,
                'city' => null,
                'postal_code' => null,
                'phone' => '+13254036444',
                'tax_id' => '123456789',
                'year_founded' => '2020',
                'ceo' => 'Peter Cop',
                'number_of_offices' => 6,
                'number_of_employees' => 6,
                'annual_revenue' => '9M',
                'cover_image' => 'companies/cover_image.png',
                'facebook' => 'https://facebook.com',
                'linkedin' => 'https://linkedin.com',
                'twitter' => 'https://twitter.com',
                'instagram' => 'https://instagram',
                'is_featured' => $yesNo[rand(0, 1)],
                'status' => BaseStatusEnum::PUBLISHED,
            ]);
        }

        return new Collection($companies);
    }

    public function headings(): array
    {
        return [
            'name' => 'Name',
            'description' => 'Description',
            'slug' => 'Slug',
            'email' => 'Email',
            'content' => 'Content',
            'account_manager' => 'Account Manager',
            'website' => 'Website',
            'logo' => 'Logo',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'address' => 'Address',
            'country' => 'Country',
            'state' => 'State',
            'city' => 'City',
            'postal_code' => 'Postal code',
            'phone' => 'Phone',
            'tax_id' => 'Tax ID',
            'year_founded' => 'Year founded',
            'ceo' => 'Ceo',
            'number_of_offices' => 'Number of offices',
            'number_of_employees' => 'Number of employees',
            'annual_revenue' => 'Annual revenue',
            'cover_image' => 'Cover image',
            'facebook' => 'Facebook',
            'linkedin' => 'Linkedin',
            'twitter' => 'Twitter',
            'instagram' => 'Instagram',
            'is_featured' => 'Is featured',
            'status' => 'Status',
        ];
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'description' => 'nullable|string|max:400',
            'slug' => 'required|string',
            'email' => 'nullable|email',
            'content' => 'nullable|string',
            'account_manager' => 'nullable|string',
            'website' => 'nullable|string',
            'logo' => 'nullable|string',
            'latitude' => 'nullable|max:20|regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/',
            'longitude' => 'nullable|max:20|regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/',
            'address' => 'nullable|string',
            'country' => 'nullable|string',
            'state' => 'nullable|string',
            'city' => 'nullable|string',
            'postal_code' => 'Postal code',
            'phone' => 'nullable|string|max:30',
            'tax_id' => 'nullable|string|max:60',
            'year_founded' => 'nullable|string',
            'ceo' => 'nullable|string|max:120',
            'number_of_offices' => 'nullable|numeric',
            'number_of_employees' => 'nullable|string|max:60',
            'annual_revenue' => 'nullable|string|max:60',
            'cover_image' => 'nullable|string',
            'facebook' => 'nullable|string',
            'linkedin' => 'nullable|string',
            'twitter' => 'nullable|string',
            'instagram' => 'nullable|string',
            'is_featured' => 'required|boolean (Yes or No)',
            'status' => 'required|enum:published,draft,pending,closed',
        ];
    }
}
