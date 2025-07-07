<?php

namespace Botble\JobBoard\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Base\Supports\Avatar;
use Botble\JobBoard\Models\Concerns\HasActiveJobsRelation;
use Botble\JobBoard\Models\Concerns\UniqueId;
use Botble\Media\Facades\RvMedia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Company extends BaseModel
{
    use HasActiveJobsRelation;
    use UniqueId;

    protected $table = 'jb_companies';

    protected $fillable = [
        'name',
        'status',
        'account_id',
        'address',
        'email',
        'phone',
        'year_founded',
        'number_of_offices',
        'number_of_employees',
        'annual_revenue',
        'description',
        'content',
        'website',
        'logo',
        'latitude',
        'longitude',
        'postal_code',
        'cover_image',
        'facebook',
        'twitter',
        'linkedin',
        'instagram',
        'ceo',
        'is_featured',
        'country_id',
        'state_id',
        'city_id',
        'tax_id',
        'unique_id',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'name' => SafeContent::class,
        'address' => SafeContent::class,
        'year_founded' => 'int',
        'number_of_offices' => 'int',
        'number_of_employees' => 'int',
        'annual_revenue' => SafeContent::class,
        'description' => SafeContent::class,
        'content' => SafeContent::class,
        'website' => SafeContent::class,
        'facebook' => SafeContent::class,
        'twitter' => SafeContent::class,
        'linkedin' => SafeContent::class,
        'instagram' => SafeContent::class,
        'ceo' => SafeContent::class,
    ];

    public function accounts(): BelongsToMany
    {
        return $this->belongsToMany(
            Account::class,
            'jb_companies_accounts',
            'company_id',
            'account_id'
        );
    }

    public function completedProfile(): bool
    {
        if (! $this->name || ! $this->email || ! $this->address || ! $this->logo) {
            return false;
        }

        return true;
    }

    public function scopeIncompleteProfile(Builder $query): Builder
    {
        return $query->where(function ($query): void {
            $query
                ->whereNull('name')
                ->orWhereNull('email')
                ->orWhereNull('address')
                ->orWhereNull('logo');
        });
    }

    public function scopeCompletedProfile(Builder $query): Builder
    {
        return $query->where(function ($query): void {
            $query
                ->whereNotNull('name')
                ->whereNotNull('email')
                ->whereNotNull('address')
                ->whereNotNull('logo');
        });
    }

    public function getLogoThumbAttribute(): string
    {
        $logo = $this->logo ?: theme_option('default_company_logo');

        if ($logo) {
            return RvMedia::getImageUrl($logo);
        }

        return (new Avatar())
            ->setBackground('#DAE2BE')
            ->setForeground('#363F42')
            ->create($this->name)
            ->setShape('square')->toBase64();
    }

    public function getCoverImageUrlAttribute(): string
    {
        $coverImage = $this->cover_image ?: theme_option('default_company_cover_image');

        return RvMedia::getImageUrl($coverImage, null, false, RvMedia::getDefaultImage());
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class, 'company_id');
    }

    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable')
            ->where('status', BaseStatusEnum::PUBLISHED);
    }

    protected static function booted(): void
    {
        self::deleting(function (Company $company): void {
            $company->jobs()->delete();
            $company->reviews()->delete();
            $company->accounts()->detach();
        });
    }
}
