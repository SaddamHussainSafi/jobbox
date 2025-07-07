<?php

namespace Botble\JobBoard\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Models\BaseModel;
use Botble\Base\Models\BaseQueryBuilder;
use Botble\JobBoard\Enums\JobStatusEnum;
use Botble\JobBoard\Enums\ModerationStatusEnum;
use Botble\JobBoard\Enums\SalaryRangeEnum;
use Botble\JobBoard\Facades\JobBoardHelper;
use Botble\JobBoard\Models\Builders\FilterJobsBuilder;
use Botble\JobBoard\Models\Concerns\UniqueId;
use Botble\Media\Facades\RvMedia;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\DB;

class Job extends BaseModel
{
    use UniqueId;

    protected $table = 'jb_jobs';

    protected $fillable = [
        'name',
        'description',
        'content',
        'company_id',
        'address',
        'status',
        'apply_url',
        'is_freelance',
        'career_level_id',
        'salary_from',
        'salary_to',
        'salary_range',
        'currency_id',
        'degree_level_id',
        'job_shift_id',
        'job_experience_id',
        'functional_area_id',
        'hide_salary',
        'number_of_positions',
        'expire_date',
        'author_id',
        'author_type',
        'views',
        'number_of_applied',
        'hide_company',
        'latitude',
        'longitude',
        'auto_renew',
        'is_featured',
        'external_apply_clicks',
        'country_id',
        'state_id',
        'city_id',
        'employer_colleagues',
        'start_date',
        'application_closing_date',
        'zip_code',
        'unique_id',
    ];

    protected $casts = [
        'status' => JobStatusEnum::class,
        'moderation_status' => ModerationStatusEnum::class,
        'salary_range' => SalaryRangeEnum::class,
        'expire_date' => 'datetime',
        'start_date' => 'date',
        'application_closing_date' => 'datetime',
        'name' => SafeContent::class,
        'description' => SafeContent::class,
        'content' => SafeContent::class,
        'address' => SafeContent::class,
        'apply_url' => SafeContent::class,
    ];

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(JobSkill::class, 'jb_jobs_skills', 'job_id', 'job_skill_id');
    }

    public function careerLevel(): BelongsTo
    {
        return $this->belongsTo(CareerLevel::class, 'career_level_id')->withDefault();
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class)->withDefault();
    }

    public function degreeLevel(): BelongsTo
    {
        return $this->belongsTo(DegreeLevel::class, 'degree_level_id')->withDefault();
    }

    public function jobShift(): BelongsTo
    {
        return $this->belongsTo(JobShift::class, 'job_shift_id')->withDefault();
    }

    public function jobExperience(): BelongsTo
    {
        return $this->belongsTo(JobExperience::class, 'job_experience_id')->withDefault();
    }

    public function functionalArea(): BelongsTo
    {
        return $this->belongsTo(FunctionalArea::class, 'functional_area_id')->withDefault();
    }

    public function jobTypes(): BelongsToMany
    {
        return $this->belongsToMany(JobType::class, 'jb_jobs_types', 'job_id', 'job_type_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id')->withDefault();
    }

    public function author(): MorphTo
    {
        return $this->morphTo()->withDefault();
    }

    protected function salaryText(): Attribute
    {
        return Attribute::make(
            get: function ($_, array $attributes = []) {
                if ($attributes['hide_salary']) {
                    return __('Attractive');
                }

                $salaryRange = strtolower($this->salary_range->label());

                $from = (float) $attributes['salary_from'];
                $to = (float) $attributes['salary_to'];

                $currency = $this->currency->getKey() ? $this->currency : get_application_currency();

                if ($from || $to) {
                    if ($from && $to) {
                        return __(':from - :to /:range', ['from' => format_price($from, $currency), 'to' => format_price($to, $currency), 'range' => $salaryRange]);
                    }

                    if ($from) {
                        return __('From :price /:range', ['price' => format_price($from, $currency), 'range' => $salaryRange]);
                    }

                    return __('Upto :price /:range', ['price' => format_price($to, $currency), 'range' => $salaryRange]);
                }

                return __('Attractive');
            }
        );
    }

    public function scopeNotExpired(Builder $query): Builder
    {
        return $query->where(function ($query): void {
            $query
                ->where('expire_date', '>=', Carbon::now()->toDateTimeString())
                ->orWhereNull('expire_date')
                ->orWhere('never_expired', true);
        });
    }

    public function scopeNotClosed(Builder $query): Builder
    {
        return $query->where(function ($query): void {
            $query
                ->where('application_closing_date', '>=', Carbon::now()->toDateTimeString())
                ->orWhereNull('application_closing_date');
        });
    }

    public function scopeExpired(Builder $query): Builder
    {
        return $query->where(function ($query): void {
            $query
                ->where('expire_date', '<', Carbon::now()->toDateTimeString())
                ->where('never_expired', false);
        });
    }

    public function scopeActive(Builder $query): Builder
    {
        // @phpstan-ignore-next-line
        return $query
            ->where(JobBoardHelper::getJobDisplayQueryConditions())
            ->notExpired();
    }

    public function scopeAddSavedApplied(Builder $query): Builder
    {
        if (auth('account')->check()) {
            // @phpstan-ignore-next-line
            $query->addApplied()->addSaved();
        }

        return $query;
    }

    public function scopeAddApplied(Builder $query): Builder
    {
        if (! auth('account')->check() || auth('account')->user()->isEmployer()) {
            return $query;
        }

        $accountId = auth('account')->id();

        return $query
            ->leftJoin('jb_applications', function ($join) use ($accountId): void {
                $join
                    ->on('jb_applications.job_id', '=', 'jb_jobs.id')
                    ->where('jb_applications.account_id', $accountId);
            })
            ->addSelect(DB::raw('IF(jb_applications.job_id IS NULL, 0, jb_applications.job_id) AS is_applied'))
            ->addSelect('jb_jobs.*');
    }

    public function scopeAddSaved(Builder $query): Builder
    {
        if (! auth('account')->check() || auth('account')->user()->isEmployer()) {
            return $query;
        }

        $accountId = auth('account')->id();

        return $query
            ->leftJoin('jb_saved_jobs', function ($join) use ($accountId): void {
                $join
                    ->on('jb_saved_jobs.job_id', '=', 'jb_jobs.id')
                    ->where('jb_saved_jobs.account_id', $accountId);
            })
            ->addSelect(DB::raw('IF(jb_saved_jobs.job_id IS NULL, 0, jb_saved_jobs.job_id) AS is_saved'))
            ->addSelect('jb_jobs.*');
    }

    public function scopeByAccount(Builder $query, int $accountId): Builder
    {
        return $query->where(function (Builder $query) use ($accountId): void {
            $query->where([
                'jb_jobs.author_id' => $accountId,
                'jb_jobs.author_type' => Account::class,
            ])
                ->orWhereHas('company.accounts', function (Builder $query) use ($accountId): void {
                    $query->where('jb_companies_accounts.account_id', $accountId);
                });
        });
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'jb_jobs_categories', 'job_id', 'category_id');
    }

    public function savedJobs(): BelongsToMany
    {
        return $this->belongsToMany(Account::class, 'jb_saved_jobs', 'job_id', 'account_id');
    }

    public function applicants(): HasMany
    {
        return $this->hasMany(JobApplication::class, 'job_id');
    }

    public function analytics(): HasMany
    {
        return $this->hasMany(Analytics::class, 'job_id');
    }

    public function canShowSavedJob(): bool
    {
        if (! auth('account')->check() || auth('account')->user()->isEmployer()) {
            return false;
        }

        return $this->is_saved !== -1;
    }

    public function canShowApplyJob(): bool
    {
        if (! auth('account')->check()) {
            return true;
        }

        if (auth('account')->user()->isEmployer()) {
            return false;
        }

        return $this->is_applied !== -1;
    }

    public function getHasCompanyAttribute(): bool
    {
        return ! $this->hide_company && $this->company->id;
    }

    public function getCompanyLogoThumbAttribute(): string
    {
        if ($this->has_company) {
            return $this->company->logo_thumb;
        }

        $logo = theme_option('default_company_logo', theme_option('logo'));

        return RvMedia::getImageUrl($logo, null, false, RvMedia::getDefaultImage());
    }

    public function getCompanyNameAttribute(): ?string
    {
        if ($this->has_company) {
            return $this->company->name;
        }

        return null;
    }

    public function getCompanyUrlAttribute(): ?string
    {
        if ($this->has_company) {
            return $this->company->url;
        }

        return null;
    }

    public function getIsExpiredAttribute(): bool
    {
        if (! $this->expire_date || $this->never_expired) {
            return false;
        }

        return $this->expire_date->lte(Carbon::now());
    }

    public function isJobOpen(): bool
    {
        $now = Carbon::now();

        if (! $this->never_expired && $now->greaterThan($this->expire_date)) {
            return false;
        }

        if ($this->application_closing_date && $now->greaterThan($this->application_closing_date)) {
            return false;
        }

        return true;
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(
            Tag::class,
            'jb_jobs_tags',
            'job_id',
            'tag_id'
        );
    }

    public function getEmployerColleaguesAttribute(?string $value): array
    {
        return json_decode((string) $value, true) ?: [];
    }

    public function setEmployerColleaguesAttribute(array $employerColleagues): void
    {
        $this->attributes['employer_colleagues'] = $employerColleagues ? json_encode($employerColleagues) : '';
    }

    public function getEmployerEmailsAttribute(): array
    {
        $emails = [];

        if ($this->author->email) {
            $emails[] = $this->author->email;
        }

        if (! empty($this->employer_colleagues)) {
            $emails = array_merge($emails, $this->employer_colleagues);
        }

        return $emails;
    }

    public function getLocationAttribute(): ?string
    {
        $displayType = setting('job_board_job_location_display', 'state_and_country');

        return match ($displayType) {
            'city_state_and_country' => ($this->city_name ? $this->city_name . ', ' : '') . ($this->state_name ? $this->state_name . ', ' : '') . $this->country->code,
            'city_and_state' => ($this->city_name ? $this->city_name . ', ' : '') . $this->state_name,
            default => ($this->state_name ? $this->state_name . ', ' : '') . $this->country->code,
        };
    }

    public function customFields(): MorphMany
    {
        return $this->morphMany(CustomFieldValue::class, 'reference', 'reference_type', 'reference_id')->with('customField.options');
    }

    protected function customFieldsArray(): Attribute
    {
        return Attribute::make(
            get: function () {
                return CustomFieldValue::getCustomFieldValuesArray($this);
            },
        );
    }

    protected static function booted(): void
    {
        self::deleting(function (Job $job): void {
            $job->analytics()->delete();
            $job->applicants()->delete();
            $job->savedJobs()->detach();
            $job->skills()->detach();
            $job->jobTypes()->detach();
            $job->categories()->detach();
            $job->tags()->detach();
            $job->customFields()->delete();
        });

        self::updating(function (): void {
            JobBoardHelper::clearJobMaxPriceCache();
        });
    }

    public function newEloquentBuilder($query): BaseQueryBuilder
    {
        return new FilterJobsBuilder($query);
    }
}
