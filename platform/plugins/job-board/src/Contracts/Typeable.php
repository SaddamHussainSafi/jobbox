<?php

namespace Botble\JobBoard\Contracts;

use Botble\Base\Models\BaseModel;

trait Typeable
{
    public function stringToArray(?string $string): array
    {
        if ($string === null) {
            return [];
        }

        return explode(',', $string);
    }

    public function yesNoToBoolean(?string $string): bool
    {
        if (! $string) {
            return false;
        }

        return strtolower($string) === 'yes';
    }

    public function stringToModelIds(?string $value, BaseModel $model, string $column = 'name'): ?array
    {
        if (! $value) {
            return null;
        }

        $items = $this->stringToArray($value);

        $ids = [];

        foreach ($items as $index => $item) {
            if (is_numeric($item)) {
                $column = 'id';
            }

            $ids[$index] = $model->where($column, $item)->value('id');
        }

        return array_filter($ids);
    }

    /**
     * @deprecated
     * Replace by stringToModelIds()
     */
    public function getIdsFromString(?string $value, BaseModel $model, string $column = 'name'): ?array
    {
        return $this->stringToModelIds($value, $model, $column);
    }
}
