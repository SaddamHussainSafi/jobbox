<?php

namespace Botble\JobBoard\Exports;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\JobBoard\Enums\ModerationStatusEnum;
use Botble\JobBoard\Enums\SalaryRangeEnum;
use Botble\JobBoard\Facades\JobBoardHelper;
use Botble\JobBoard\Models\CareerLevel;
use Botble\JobBoard\Models\Category;
use Botble\JobBoard\Models\Company;
use Botble\JobBoard\Models\Currency;
use Botble\JobBoard\Models\DegreeLevel;
use Botble\JobBoard\Models\FunctionalArea;
use Botble\JobBoard\Models\JobExperience;
use Botble\JobBoard\Models\JobShift;
use Botble\JobBoard\Models\JobSkill;
use Botble\JobBoard\Models\JobType;
use Botble\JobBoard\Models\Tag;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JobsTemplateExport implements FromCollection, WithHeadings
{
    use Exportable;

    public function collection(): Collection
    {
        $yesNo = ['Yes', 'No'];

        $currency = Currency::query()->inRandomOrder()->value('title');
        $company = Company::query()->inRandomOrder()->value('name');
        $careerLevel = CareerLevel::query()->inRandomOrder()->value('name');
        $degreeLevel = DegreeLevel::query()->inRandomOrder()->value('name');
        $jobShift = JobShift::query()->inRandomOrder()->value('name');
        $jobExperience = JobExperience::query()->inRandomOrder()->value('name');
        $functionalArea = FunctionalArea::query()->inRandomOrder()->value('name');
        $skills = JobSkill::query()->inRandomOrder()->take(rand(1, 3))->pluck('name')->implode(',');
        $categories = Category::query()->inRandomOrder()->take(rand(1, 3))->pluck('name')->implode(',');
        $types = JobType::query()->inRandomOrder()->take(rand(1, 3))->pluck('name')->implode(',');
        $tags = Tag::query()->inRandomOrder()->take(rand(1, 3))->pluck('name')->implode(',');

        $data = [
            [
                'name' => 'Frontend Developer',
                'description' => 'Voluptatem earum occaecati ut ea. Distinctio accusantium sapiente molestiae vitae dolorem numquam quidem.',
            ],
            [
                'name' => 'Senior System Engineer',
                'description' => null,
            ],
            [
                'name' => 'Digital Marketing Manager',
                'description' => null,
            ],
            [
                'name' => 'UI / UX Designer fulltime',
                'description' => 'Voluptatem earum occaecati ut ea. Distinctio accusantium sapiente molestiae vitae dolorem numquam quidem.',
            ],
        ];

        $jobs = [];

        foreach ($data as $item) {
            $job = [
                'is_freelance' => $yesNo[rand(0, 1)],
                'hide_salary' => $yesNo[rand(0, 1)],
                'hide_company' => $yesNo[rand(0, 1)],
                'number_of_positions' => 16,
                'is_featured' => $yesNo[rand(0, 1)],
                'auto_renew' => $yesNo[rand(0, 1)],
                'latitude' => 10.823,
                'longitude' => 20.212,
                'content' => 'Content',
                'apply_url' => 'https://example.com',
                'address' => '8642 Yule Street, Arvada CO 80007',
                'country' => null,
                'state' => null,
                'city' => null,
                'company_id' => $company,
                'career_level_id' => $careerLevel,
                'salary_from' => rand(100, 1000),
                'salary_to' => rand(1000, 100000),
                'salary_range' => SalaryRangeEnum::MONTHLY,
                'skills' => $skills,
                'categories' => $categories,
                'types' => $types,
                'tags' => $tags,
                'currency_id' => $currency,
                'degree_level_id' => $degreeLevel,
                'job_shift_id' => $jobShift,
                'job_experience_id' => $jobExperience,
                'functional_area_id' => $functionalArea,
                'start_date' => Carbon::now()->toDateString(),
                'expire_date' => Carbon::now()->addDays(61)->toDateString(),
                'application_closing_date' => Carbon::now()->subDays(30)->toDateString(),
                'never_expired' => $yesNo[rand(0, 1)],
                'employer_colleagues' => null,
                'moderation_status' => ModerationStatusEnum::APPROVED,
                'status' => BaseStatusEnum::PUBLISHED,
            ];

            if (JobBoardHelper::isZipCodeEnabled()) {
                $job = array_merge($job, [
                   'zip_code' => null,
                ]);
            }

            $jobs[] = array_merge($item, $job);
        }

        return new Collection($jobs);
    }

    public function headings(): array
    {
        $headings = [
            'name' => 'Name',
            'description' => 'Description',
            'is_freelance' => 'Is freelancer?',
            'hide_salary' => 'Hide salary?',
            'hide_company' => 'Hide company?',
            'number_of_positions' => 'Number of positions',
            'is_featured' => 'Is featured?',
            'auto_renew' => 'Auto review?',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'content' => 'Content',
            'apply_url' => 'Apply URL',
            'address' => 'Address',
            'country' => 'Country',
            'state' => 'State',
            'city' => 'City',
            'company_id' => 'Company name',
            'career_level_id' => 'Career level',
            'salary_from' => 'Salary from',
            'salary_to' => 'Salary to',
            'salary_range' => 'Salary range',
            'skills' => 'Skills',
            'categories' => 'Categories',
            'types' => 'Types',
            'tags' => 'Tags',
            'currency_id' => 'Currency',
            'degree_level_id' => 'Degree level',
            'job_shift_id' => 'Job shift',
            'job_experience_id' => 'Job experience',
            'functional_area_id' => 'Functional area',
            'start_date' => 'Start date',
            'expire_date' => 'Expire date',
            'application_closing_date' => 'Application closing date',
            'never_expired' => 'Never expired?',
            'employer_colleagues' => 'Employer colleagues',
            'moderation_status' => 'Moderation status',
            'status' => 'Status',
        ];

        if (JobBoardHelper::isZipCodeEnabled()) {
            $headings = array_merge($headings, [
                'zip_code' => 'Zip code',
            ]);
        }

        return $headings;
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|min:3|max:255',
            'description' => 'nullable|string|max:400',
            'content' => 'nullable|string',
            'apply_url' => 'nullable|string',
            'company_id' => 'nullable|[Company name|Company id]',
            'career_level_id' => 'nullable|[Career level name|Career level id]',
            'salary_from' => 'nullable|decimal',
            'salary_to' => 'nullable|decimal',
            'salary_range' => 'nullable|enum:hourly,weekly,monthly,yearly',
            'hide_salary' => 'nullable|boolean (Yes or No)',
            'skills' => 'nullable|multiple|[skill name, skill id] (separate by ,)',
            'categories' => 'nullable|multiple|[Job skill name, Job skill id] (separate by ,)',
            'types' => 'nullable|multiple|[Job type name, Job type id] (separate by ,)',
            'tags' => 'nullable|multiple|[Job tag name, Job tag id] (separate by ,)',
            'currency_id' => 'nullable|[Currency name]',
            'degree_level_id' => 'nullable|[Degree level name|Degree level id]',
            'job_shift_id' => 'nullable|[Job shift name|Job shift id]',
            'job_experience_id' => 'nullable|[Job experience name|Job experience id]',
            'functional_area_id' => 'nullable|[Functional area name|Functional area id]',
            'is_freelance' => 'required|boolean (Yes or No)',
            'hide_company' => 'required|boolean (Yes or No)',
            'number_of_positions' => 'required|integer',
            'is_featured' => 'required|boolean (Yes or No)',
            'auto_renew' => 'required|boolean (Yes or No)',
            'address' => 'nullable|string',
            'country' => 'nullable|string',
            'state' => 'nullable|string',
            'city' => 'nullable|string',
            'latitude' => 'nullable|max:20|regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/',
            'longitude' => 'nullable|max:20|regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/',
            'start_date' => 'nullable|date_format:Y-m-d',
            'expire_date' => 'nullable|date_format:Y-m-d',
            'application_closing_date' => 'nullable|date_format:Y-m-d',
            'never_expired' => 'required|boolean (Yes or No)',
            'employer_colleagues' => 'nullable|multiple (Separate by ,)',
            'moderation_status' => 'required|enum:approved,pending,rejected (default: pending)',
            'status' => 'required|enum:published,draft,pending,closed',
        ];

        if (JobBoardHelper::isZipCodeEnabled()) {
            $rules = array_merge($rules, ['zip_code' => 'nullable|string|max:20']);
        }

        return $rules;
    }
}
