<?php

namespace Botble\JobBoard\Exports;

use Botble\JobBoard\Models\Account;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AccountsExport implements FromCollection, WithHeadings, WithMapping
{
    use Exportable;

    public function collection(): Collection
    {
        return Account::query()
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
            'ID',
            'First name',
            'Last name',
            'Description',
            'Gender',
            'Email',
            'Date of birth',
            'Phone',
            'Address',
            'Bio',
            'Type',
            'Resume',
            'Cover letter',
            'Is public profile',
            'Is featured',
            'Available for hiring',
            'Country',
            'State',
            'City',
            'Views',
            'Created at',
            'Updated at',
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->first_name,
            $row->last_name,
            $row->description,
            $row->gender,
            $row->email,
            $row->dob,
            $row->phone,
            $row->address,
            $row->bio,
            $row->type,
            $row->resume_url,
            $row->cover_letter,
            $row->is_public_profile ? 'Yes' : 'No',
            $row->is_featured ? 'Yes' : 'No',
            $row->available_for_hiring ? 'Yes' : 'No',
            $row->country->name,
            $row->state->name,
            $row->city->name,
            $row->views,
            $row->created_at,
            $row->updated_at,
        ];
    }
}
