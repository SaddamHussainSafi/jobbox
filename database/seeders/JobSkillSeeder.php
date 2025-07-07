<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\JobBoard\Models\JobSkill;

class JobSkillSeeder extends BaseSeeder
{
    public function run(): void
    {
        JobSkill::query()->truncate();

        $data = [
            'JavaScript',
            'PHP',
            'Python',
            'Laravel',
            'CakePHP',
            'WordPress',
            'Flutter',
            'FilamentPHP',
            'React.js',
        ];

        foreach ($data as $item) {
            JobSkill::query()->create(['name' => $item]);
        }
    }
}
