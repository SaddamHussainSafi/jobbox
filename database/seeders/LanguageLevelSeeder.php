<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\JobBoard\Models\LanguageLevel;

class LanguageLevelSeeder extends BaseSeeder
{
    public function run(): void
    {
        LanguageLevel::query()->truncate();

        $data = [
            'Expert',
            'Intermediate',
            'Beginner',
        ];

        foreach ($data as $item) {
            LanguageLevel::query()->create(['name' => $item]);
        }
    }
}
