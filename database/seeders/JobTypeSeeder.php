<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\JobBoard\Models\JobType;

class JobTypeSeeder extends BaseSeeder
{
    public function run(): void
    {
        JobType::query()->truncate();

        $data = [
            [
                'name' => 'Contract',
            ],
            [
                'name' => 'Freelance',
            ],
            [
                'name' => 'Full Time',
                'is_default' => 1,
            ],
            [
                'name' => 'Internship',
            ],
            [
                'name' => 'Part Time',
            ],
        ];

        foreach ($data as $item) {
            JobType::query()->create($item);
        }
    }
}
