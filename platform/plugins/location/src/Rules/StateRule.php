<?php

namespace Botble\Location\Rules;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Location\Models\State;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;

class StateRule implements DataAwareRule, Rule
{
    protected array $data = [];

    public function __construct(protected ?string $countryKey = '')
    {
    }

    public function setData($data): self
    {
        $this->data = $data;

        return $this;
    }

    public function passes($attribute, $value): bool
    {
        $condition = [
            'id' => $value,
            'status' => BaseStatusEnum::PUBLISHED,
        ];

        $query = State::query()->where($condition);

        if ($this->countryKey && ($countryId = Arr::get($this->data, $this->countryKey))) {
            $query = $query
                ->whereHas('country', function ($query) use ($countryId): void {
                    $query
                        ->where('id', $countryId)
                        ->orWhere('code', $countryId);
                });
        }

        return $query->exists();
    }

    public function message(): string
    {
        return trans('validation.exists');
    }
}
