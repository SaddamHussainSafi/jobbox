<?php

namespace Botble\JobBoard\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Models\BaseModel;
use Botble\JobBoard\Enums\CustomFieldEnum;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;

class CustomField extends BaseModel
{
    protected $table = 'jb_custom_fields';

    protected $fillable = [
        'name',
        'type',
        'order',
        'is_global',
        'authorable_type',
        'authorable_id',
    ];

    protected $casts = [
        'type' => CustomFieldEnum::class,
        'is_global' => 'bool',
        'order' => 'int',
        'name' => SafeContent::class,
    ];

    protected static function booted(): void
    {
        static::deleted(function (CustomField $customField): void {
            $customField->options()->delete();
            $customField->customFieldValue()->delete();
        });
    }

    public function authorable(): MorphTo
    {
        return $this->morphTo();
    }

    public function options(): HasMany
    {
        return $this->hasMany(CustomFieldOption::class, 'custom_field_id');
    }

    public function customFieldValue(): HasOne
    {
        return $this->hasOne(CustomFieldValue::class);
    }

    public function saveRelations(array $data = []): void
    {
        if (Arr::get($data, 'type') === CustomFieldEnum::TEXT) {
            Arr::forget($data, 'options');
        }

        $options = $this->formatOptions(Arr::get($data, 'options', []));

        $this->options()
            ->whereNotIn('id', collect($options)->pluck('id')->all())
            ->delete();

        if (count($options)) {
            $this->options()->saveMany($options);
        }
    }

    protected function formatOptions(array $options = []): array
    {
        if (empty($options)) {
            return [];
        }

        $customFieldOptions = [];

        foreach ($options as $item) {
            $option = null;

            if (Arr::exists($item, 'id')) {
                $option = CustomFieldOption::query()->find($item['id']);
                $option->fill($item);
            }

            if (! $option) {
                $option = new CustomFieldOption($item);
            }

            $customFieldOptions[] = $option;
        }

        return $customFieldOptions;
    }
}
