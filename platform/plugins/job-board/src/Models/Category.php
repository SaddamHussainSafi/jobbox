<?php

namespace Botble\JobBoard\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Contracts\HasTreeCategory as HasTreeCategoryContract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Base\Traits\HasTreeCategory;
use Botble\JobBoard\Models\Concerns\HasActiveJobsRelation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends BaseModel implements HasTreeCategoryContract
{
    use HasActiveJobsRelation;
    use HasTreeCategory;

    protected $table = 'jb_categories';

    protected $fillable = [
        'name',
        'description',
        'order',
        'is_default',
        'is_featured',
        'status',
        'parent_id',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'name' => SafeContent::class,
        'description' => SafeContent::class,
    ];

    protected static function booted(): void
    {
        static::deleting(function (Category $category): void {
            foreach ($category->children as $child) {
                $child->parent_id = $category->parent_id;
                $child->save();
            }

            $category->jobs()->detach();
        });
    }

    public function jobs(): BelongsToMany
    {
        return $this->belongsToMany(
            Job::class,
            'jb_jobs_categories',
            'category_id',
            'job_id'
        );
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id')->withDefault();
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function activeChildren(): HasMany
    {
        return $this
            ->children()
            ->wherePublished()
            ->with(['slugable', 'activeChildren']);
    }
}
