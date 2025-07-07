<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\JobBoard\Models\DegreeLevel;

class DegreeLevelSeeder extends BaseSeeder
{
    public function run(): void
    {
        DegreeLevel::query()->truncate();

        $data = [
            1 => 'Non-Matriculation',
            2 => 'Matriculation/O-Level',
            3 => 'Intermediate/A-Level',
            4 => 'Bachelors',
            5 => 'Masters',
            6 => 'MPhil/MS',
            7 => 'PHD/Doctorate',
            8 => 'Certification',
            9 => 'Diploma',
            10 => 'Short Course',
        ];

        foreach ($data as $id => $item) {
            DegreeLevel::query()->create(['id' => $id, 'name' => $item]);
        }
    }
}
