<?php

namespace Botble\JobBoard\Models\Concerns;

use Illuminate\Support\Str;

trait UniqueId
{
    public function generateUniqueId(bool $force = false): float|string|null
    {
        if (
            ! $force
            && (
                ! setting('job_board_auto_generate_unique_id', true) ||
                ! setting('job_board_unique_id_format')
            )
        ) {
            return null;
        }

        $setting = (string) setting('job_board_unique_id_format');

        if (! Str::contains($setting, ['[%s]', '[%d]', '[%S]', '[%D]', '%s', '%d'])) {
            return $setting . (mt_rand(10000, 99999) + time());
        }

        $uniqueId = str_replace(
            ['[%s]', '[%S]'],
            strtoupper(Str::random(5)),
            $setting
        );

        $uniqueId = str_replace(
            ['[%d]', '[%D]'],
            (string) mt_rand(10000, 99999),
            $uniqueId
        );

        foreach (explode('%s', $uniqueId) as $ignored) {
            $uniqueId = preg_replace('/%s/i', strtoupper(Str::random(1)), $uniqueId, 1);
        }

        foreach (explode('%d', $uniqueId) as $ignored) {
            $uniqueId = preg_replace('/%d/i', (string) mt_rand(0, 9), $uniqueId, 1);
        }

        if ($this->query()->where('unique_id', $uniqueId)->exists()) {
            return $uniqueId . (mt_rand(10000, 99999) + time());
        }

        return $uniqueId;
    }
}
