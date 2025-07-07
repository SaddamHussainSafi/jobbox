<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\JobBoard\Models\DegreeType;

class DegreeTypeSeeder extends BaseSeeder
{
    public function run(): void
    {
        DegreeType::query()->truncate();

        $data = [
            [
                'name' => 'Matric in Arts',
                'degree_level_id' => 2,
            ],
            [
                'name' => 'Matric in Science',
                'degree_level_id' => 2,
            ],
            [
                'name' => 'O-Levels',
                'degree_level_id' => 2,
            ],
            [
                'name' => 'A-Levels',
                'degree_level_id' => 3,
            ],
            [
                'name' => 'Faculty of Arts',
                'degree_level_id' => 3,
            ],
            [
                'name' => 'Faculty of Science (Pre-medical)',
                'degree_level_id' => 3,
            ],
            [
                'name' => 'Faculty of Science (Pre-Engineering)',
                'degree_level_id' => 3,
            ],
            [
                'name' => 'Intermediate in Computer Science',
                'degree_level_id' => 3,
            ],
            [
                'name' => 'Intermediate in Commerce',
                'degree_level_id' => 3,
            ],
            [
                'name' => 'Intermediate in General Science',
                'degree_level_id' => 3,
            ],
            [
                'name' => 'Bachelors in Arts',
                'degree_level_id' => 4,
            ],
            [
                'name' => 'Bachelors in Architecture',
                'degree_level_id' => 4,
            ],
            [
                'name' => 'Bachelors in Business Administration',
                'degree_level_id' => 4,
            ],
            [
                'name' => 'Bachelors in Commerce',
                'degree_level_id' => 4,
            ],
            [
                'name' => 'Bachelors of Dental Surgery',
                'degree_level_id' => 4,
            ],
            [
                'name' => 'Bachelors of Education',
                'degree_level_id' => 4,
            ],
            [
                'name' => 'Bachelors in Engineering',
                'degree_level_id' => 4,
            ],
            [
                'name' => 'Bachelors in Pharmacy',
                'degree_level_id' => 4,
            ],
            [
                'name' => 'Bachelors in Science',
                'degree_level_id' => 4,
            ],
            [
                'name' => 'Bachelors of Science in Nursing (Registered Nursing)',
                'degree_level_id' => 4,
            ],
            [
                'name' => 'Bachelors in Law',
                'degree_level_id' => 4,
            ],
            [
                'name' => 'Bachelors in Technology',
                'degree_level_id' => 4,
            ],
            [
                'name' => 'BCS/BS',
                'degree_level_id' => 4,
            ],
            [
                'name' => 'Doctor of Veterinary Medicine',
                'degree_level_id' => 4,
            ],
            [
                'name' => 'MBBS',
                'degree_level_id' => 4,
            ],
            [
                'name' => 'Post Registered Nursing B.S.',
                'degree_level_id' => 4,
            ],
            [
                'name' => 'Masters in Arts',
                'degree_level_id' => 5,
            ],
            [
                'name' => 'Masters in Business Administration',
                'degree_level_id' => 5,
            ],
            [
                'name' => 'Masters in Commerce',
                'degree_level_id' => 5,
            ],
            [
                'name' => 'Masters of Education',
                'degree_level_id' => 5,
            ],
            [
                'name' => 'Masters in Law',
                'degree_level_id' => 5,
            ],
            [
                'name' => 'Masters in Science',
                'degree_level_id' => 5,
            ],
            [
                'name' => 'Executive Masters in Business Administration',
                'degree_level_id' => 5,
            ],
        ];

        foreach ($data as $item) {
            DegreeType::query()->create($item);
        }
    }
}
