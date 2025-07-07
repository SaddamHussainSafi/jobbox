<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\JobBoard\Models\CareerLevel;

class CareerLevelSeeder extends BaseSeeder
{
    public function run(): void
    {
        CareerLevel::query()->truncate();

        $data = [
            'Department Head',
            'Entry Level',
            'Experienced Professional',
            'GM / CEO / Country Head / President',
            'Intern/Student',
        ];

        foreach ($data as $item) {
            CareerLevel::query()->create(['name' => $item]);
        }
    }
}
