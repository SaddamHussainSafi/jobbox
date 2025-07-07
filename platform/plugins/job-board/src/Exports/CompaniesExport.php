<?php

namespace Botble\JobBoard\Exports;

use Botble\JobBoard\Models\Company;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CompaniesExport implements FromCollection, WithHeadings, WithMapping
{
    use Exportable;

    public function collection(): Collection
    {
        return Company::query()
            ->with([
                'country',
                'state',
                'city',
            ])
            ->get();
    }

    public function headings(): array
    {
        return [
            'Name',
            'Description',
            'Slug',
            'Email',
            'Content',
            'Account Manager',
            'Website',
            'Logo',
            'Latitude',
            'Longitude',
            'Address',
            'Country',
            'City',
            'State',
            'Postal code',
            'Phone',
            'Tax ID',
            'Year founded',
            'Ceo',
            'Number of offices',
            'Number of employees',
            'Annual Revenue',
            'Cover image',
            'Facebook',
            'Linkedin',
            'Twitter',
            'Instagram',
            'Is featured',
            'Status',
        ];
    }

    public function map($row): array
    {
        return [
            $row->name,
            $row->description,
            $row->slug,
            $row->email,
            $row->content,
            implode(',', $row->accounts->pluck('first_name')->all()),
            $row->website,
            $row->logo,
            $row->latitude,
            $row->longitude,
            $row->address,
            $row->country->name,
            $row->city->name,
            $row->state->name,
            $row->postal_code,
            $row->phone,
            $row->tax_id,
            $row->year_founded,
            $row->ceo,
            $row->number_of_offices,
            $row->number_of_employees,
            $row->annual_revenue,
            $row->cover_image,
            $row->facebook,
            $row->linkedin,
            $row->twitter,
            $row->instagram,
            $row->is_featured ? 'Yes' : 'No',
            $row->status,
        ];
    }
}
