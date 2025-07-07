<?php

namespace Botble\JobBoard\Exports;

use Botble\JobBoard\Facades\JobBoardHelper;
use Botble\JobBoard\Models\Job;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class JobsExport implements FromCollection, WithHeadings, WithMapping
{
    use Exportable;

    public function collection(): Collection
    {
        return Job::query()
            ->with([
                'country',
                'state',
                'city',
                'company',
                'careerLevel',
                'degreeLevel',
                'jobShift',
                'jobExperience',
                'functionalArea',
                'currency',
                'categories',
                'skills',
                'jobTypes',
                'tags',
            ])
            ->get();
    }

    public function map($row): array
    {
        $data = [
            $row->id,
            $row->name,
            $row->description,
            $row->content,
            $row->apply_url,
            $row->company->name,
            $row->address,
            $row->country->name,
            $row->state->name,
            $row->city->name,
            $this->booleanToYesNo($row->is_freelance),
            $row->careerLevel->name,
            $row->salary_from,
            $row->salary_to,
            $row->salary_range,
            $row->currency->title,
            $row->degreeLevel->name,
            $row->jobShift->name,
            $row->jobExperience->name,
            $row->functionalArea->name,
            $this->booleanToYesNo($row->hide_salary),
            $row->number_of_positions,
            $row->expire_date,
            $row->author_id,
            $row->author_type,
            $row->views,
            $row->number_of_applied,
            $this->booleanToYesNo($row->hide_company),
            $row->latitude,
            $row->longitude,
            $this->booleanToYesNo($row->auto_renew),
            $row->external_apply_clicks,
            $this->booleanToYesNo($row->never_expired),
            $this->booleanToYesNo($row->is_featured),
            $row->status,
            $row->moderation_status,
            $row->created_at,
            $row->updated_at,
            $row->employer_colleagues,
            $row->start_date,
            $row->application_closing_date,
            implode(',', $row->skills->pluck('name')->all()),
            implode(',', $row->categories->pluck('name')->all()),
            implode(',', $row->jobTypes->pluck('name')->all()),
            implode(',', $row->tags->pluck('name')->all()),
        ];

        if (JobBoardHelper::isZipCodeEnabled()) {
            $data[] = $row->zip_code;
        }

        return $data;
    }

    public function headings(): array
    {
        $headings = [
            'ID',
            'Name',
            'Description',
            'Content',
            'Apply URL',
            'Company',
            'Address',
            'Country',
            'State',
            'City',
            'Is freelancer?',
            'Career level',
            'Salary from',
            'Salary to',
            'Salary range',
            'Currency',
            'Degree level',
            'Job shift',
            'Job experience',
            'Functional area',
            'Hide salary?',
            'Number of positions',
            'Expire date',
            'Author ID',
            'Author type',
            'Views',
            'Number of applied',
            'Hide company?',
            'Latitude',
            'Longitude',
            'Auto review?',
            'External apply clicks',
            'Never expired?',
            'Is featured?',
            'Status',
            'Moderation status',
            'Created at',
            'Updated at',
            'Employer colleagues',
            'Start date',
            'Application closing date',
            'Skills',
            'Categories',
            'Types',
            'Tags',
        ];

        if (JobBoardHelper::isZipCodeEnabled()) {
            $headings[] = 'Zip code';
        }

        return $headings;
    }

    protected function booleanToYesNo(?int $value): string
    {
        return $value ? 'Yes' : 'No';
    }
}
