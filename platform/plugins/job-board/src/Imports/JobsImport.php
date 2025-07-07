<?php

namespace Botble\JobBoard\Imports;

use Botble\Base\Events\CreatedContentEvent;
use Botble\JobBoard\Contracts\OnSuccesses;
use Botble\JobBoard\Contracts\Typeable;
use Botble\JobBoard\Contracts\Validatable;
use Botble\JobBoard\Facades\JobBoardHelper;
use Botble\JobBoard\Models\CareerLevel;
use Botble\JobBoard\Models\Category;
use Botble\JobBoard\Models\Company;
use Botble\JobBoard\Models\Currency;
use Botble\JobBoard\Models\DegreeLevel;
use Botble\JobBoard\Models\FunctionalArea;
use Botble\JobBoard\Models\Job;
use Botble\JobBoard\Models\JobExperience;
use Botble\JobBoard\Models\JobShift;
use Botble\JobBoard\Models\JobSkill;
use Botble\JobBoard\Models\JobType;
use Botble\JobBoard\Models\Tag;
use Botble\Location\Models\City;
use Botble\Location\Models\Country;
use Botble\Location\Models\State;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithValidation;

class JobsImport implements
    ToModel,
    WithValidation,
    WithChunkReading,
    WithHeadingRow,
    WithMapping
{
    use Importable;
    use SkipsFailures;
    use SkipsErrors;
    use Validatable;
    use OnSuccesses;
    use Typeable;

    protected bool $updateExisting = false;

    public function __construct(protected Request $request)
    {
    }

    public function setUpdateExisting(bool $updateExisting): self
    {
        $this->updateExisting = $updateExisting;

        return $this;
    }

    protected function formatDate($date): ?string
    {
        if (empty($date)) {
            return null;
        }

        try {
            return Carbon::parse($date)->format('Y-m-d');
        } catch (Exception) {
            return null;
        }
    }

    protected function formatEmployerColleagues($value): array
    {
        if (empty($value)) {
            return [];
        }

        if (is_string($value)) {
            // Try to parse as JSON first
            try {
                $decoded = json_decode($value, true);
                if (is_array($decoded)) {
                    return $decoded;
                }
            } catch (Exception) {
                // Ignore JSON parsing errors
            }

            // If not JSON, try to split by comma
            return array_map('trim', explode(',', $value));
        }

        return is_array($value) ? $value : [];
    }

    public function mapRelationships(array $row, array $data): array
    {
        $data['country_id'] = Arr::first($this->stringToModelIds(Arr::get($row, 'country'), new Country()));
        $data['state_id'] = Arr::first($this->stringToModelIds(Arr::get($row, 'state'), new State()));
        $data['city_id'] = Arr::first($this->stringToModelIds(Arr::get($row, 'city'), new City()));
        $data['company_id'] = Arr::first($this->stringToModelIds(Arr::get($row, 'company'), new Company()));
        $data['currency_id'] = Arr::first($this->stringToModelIds(Arr::get($row, 'currency'), new Currency(), 'title'));
        $data['career_level_id'] = Arr::first($this->stringToModelIds(Arr::get($row, 'career_level'), new CareerLevel()));
        $data['degree_level_id'] = Arr::first($this->stringToModelIds(Arr::get($row, 'degree_level'), new DegreeLevel()));
        $data['job_shift_id'] = Arr::first($this->stringToModelIds(Arr::get($row, 'job_shift'), new JobShift()));
        $data['job_experience_id'] = Arr::first($this->stringToModelIds(Arr::get($row, 'job_experience'), new JobExperience()));
        $data['functional_area_id'] = Arr::first($this->stringToModelIds(Arr::get($row, 'functional_area'), new FunctionalArea()));
        $data['categories'] = $this->stringToModelIds(Arr::get($row, 'categories'), new Category());
        $data['skills'] = $this->stringToModelIds(Arr::get($row, 'skills'), new JobSkill());
        $data['types'] = $this->stringToModelIds(Arr::get($row, 'types'), new JobType());
        $data['tags'] = $this->stringToModelIds(Arr::get($row, 'tags'), new Tag());

        return $data;
    }

    public function model(array $row): Model
    {
        // Create a clean array with only the fields we need
        $data = [
            'name' => Arr::get($row, 'name'),
            'description' => Arr::get($row, 'description'),
            'content' => Arr::get($row, 'content'),
            'apply_url' => Arr::get($row, 'apply_url'),
            'address' => Arr::get($row, 'address'),
            'is_freelance' => $this->yesNoToBoolean(Arr::get($row, 'is_freelance', false)),
            'salary_from' => Arr::get($row, 'salary_from'),
            'salary_to' => Arr::get($row, 'salary_to'),
            'salary_range' => Arr::get($row, 'salary_range'),
            'hide_salary' => $this->yesNoToBoolean(Arr::get($row, 'hide_salary', false)),
            'number_of_positions' => Arr::get($row, 'number_of_positions', 0),
            'hide_company' => $this->yesNoToBoolean(Arr::get($row, 'hide_company', false)),
            'latitude' => Arr::get($row, 'latitude'),
            'longitude' => Arr::get($row, 'longitude'),
            'is_featured' => $this->yesNoToBoolean(Arr::get($row, 'is_featured', false)),
            'auto_renew' => $this->yesNoToBoolean(Arr::get($row, 'auto_renew', false)),
            'never_expired' => ! Arr::get($row, 'expire_date') || $this->yesNoToBoolean(Arr::get($row, 'never_expired')),
            'status' => Arr::get($row, 'status', 'published'),
            'moderation_status' => Arr::get($row, 'moderation_status', 'approved'),
        ];

        // Format date fields
        foreach (['expire_date', 'start_date', 'application_closing_date'] as $dateField) {
            if (isset($row[$dateField])) {
                $data[$dateField] = $this->formatDate($row[$dateField]);
            }
        }

        // Format employer_colleagues field
        if (isset($row['employer_colleagues'])) {
            $data['employer_colleagues'] = $this->formatEmployerColleagues($row['employer_colleagues']);
        }

        // Add zip_code if enabled
        if (JobBoardHelper::isZipCodeEnabled() && isset($row['zip_code'])) {
            $data['zip_code'] = $row['zip_code'];
        }

        // Map relationships
        $relationships = $this->mapRelationships($row, []);

        // Merge the relationships into the data
        $data = array_merge($data, $relationships);

        $uniqueId = Arr::get($row, 'unique_id');
        $id = Arr::get($row, 'id');
        $job = null;

        // Check if we should update existing jobs and if the job exists
        // First try to find by ID if provided
        if ($id) {
            $job = Job::query()->find($id);
        }

        // If not found by ID and unique_id is provided, try to find by unique_id
        if (! $job && $uniqueId) {
            $job = Job::query()->where('unique_id', $uniqueId)->first();
        }

        // If unique_id is empty, set it to null to avoid unique constraint issues
        if (empty($uniqueId)) {
            $data['unique_id'] = null;
        } else {
            $data['unique_id'] = $uniqueId;
        }

        // If job doesn't exist or we're not updating existing jobs, create a new one
        if (! $job) {
            $job = new Job();
        }

        $job->forceFill(Arr::except($data, ['skills', 'categories', 'types', 'tags']));
        $job->save();

        $job->skills()->sync(Arr::get($data, 'skills', []));
        $job->categories()->sync(Arr::get($data, 'categories', []));
        $job->jobTypes()->sync(Arr::get($data, 'types', []));
        $job->tags()->sync(Arr::get($data, 'tags', []));

        $job->author()->associate(auth()->user());

        $this->request->merge([
            'slug' => Str::slug($job->name),
            'is_slug_editable' => true,
        ]);

        event(new CreatedContentEvent(JOB_MODULE_SCREEN_NAME, $this->request, $job));

        $this->onSuccess($job);

        return $job;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function handle(array $rows): void
    {
        foreach ($rows as $row) {
            $this->model($row);
        }
    }

    public function map($row): array
    {
        $job = [
            'name' => Arr::get($row, 'name'),
            'description' => Arr::get($row, 'description'),
            'content' => Arr::get($row, 'content'),
            'apply_url' => Arr::get($row, 'apply_url'),
            'address' => Arr::get($row, 'address'),
            'is_freelance' => $this->yesNoToBoolean(Arr::get($row, 'is_freelance', false)),
            'salary_from' => Arr::get($row, 'salary_from'),
            'salary_to' => Arr::get($row, 'salary_to'),
            'salary_range' => Arr::get($row, 'salary_range'),
            'hide_salary' => $this->yesNoToBoolean(Arr::get($row, 'hide_salary', false)),
            'number_of_positions' => Arr::get($row, 'number_of_positions', 0),
            'expire_date' => Arr::get($row, 'expire_date'),
            'hide_company' => $this->yesNoToBoolean(Arr::get($row, 'hide_company', false)),
            'latitude' => Arr::get($row, 'latitude'),
            'longitude' => Arr::get($row, 'longitude'),
            'is_featured' => $this->yesNoToBoolean(Arr::get($row, 'is_featured', false)),
            'auto_renew' => $this->yesNoToBoolean(Arr::get($row, 'auto_renew', false)),
            'never_expired' => ! Arr::get($row, 'expire_date') || $this->yesNoToBoolean(Arr::get($row, 'never_expired')),
            'employer_colleagues' => $this->stringToArray(Arr::get($row, 'employer_colleagues')),
            'start_date' => Arr::get($row, 'start_date'),
            'application_closing_date' => Arr::get($row, 'application_closing_date'),
            'status' => Arr::get($row, 'status'),
            'moderation_status' => Arr::get($row, 'moderation_status'),
        ];

        if (JobBoardHelper::isZipCodeEnabled()) {
            $job = array_merge($job, [
                'zip_code' => Arr::get($row, 'zip_code'),
            ]);
        }

        // Map relationships
        $mappedRelationships = $this->mapRelationships($row, []);

        // Merge the mapped relationships with the job data
        return array_merge($job, $mappedRelationships);
    }
}
