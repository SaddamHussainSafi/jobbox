<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\JobBoard\Models\JobExperience;

class JobExperienceSeeder extends BaseSeeder
{
    public function run(): void
    {
        JobExperience::query()->truncate();

        $data = [
            'Fresh',
            'Less Than 1 Year',
            '1 Year',
            '2 Year',
            '3 Year',
            '4 Year',
            '5 Year',
            '6 Year',
            '7 Year',
            '8 Year',
            '9 Year',
            '10 Year',
        ];

        foreach ($data as $index => $item) {
            JobExperience::query()->create([
                'name' => $item,
                'order' => $index,
            ]);
        }
    }
}
