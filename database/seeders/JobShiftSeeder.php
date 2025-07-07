<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\JobBoard\Models\JobShift;

class JobShiftSeeder extends BaseSeeder
{
    public function run(): void
    {
        JobShift::query()->truncate();

        $data = [
            'First Shift (Day)',
            'Second Shift (Afternoon)',
            'Third Shift (Night)',
            'Rotating',
        ];

        foreach ($data as $item) {
            JobShift::query()->create(['name' => $item]);
        }
    }
}
