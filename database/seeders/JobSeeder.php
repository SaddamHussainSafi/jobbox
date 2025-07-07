<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\JobBoard\Database\Traits\HasJobSeeder;

class JobSeeder extends BaseSeeder
{
    use HasJobSeeder;

    public function run(): void
    {
        $this->uploadFiles('jobs');

        // Create job tags using the trait method
        $this->createJobTags($this->getDefaultJobTags());

        // Create jobs using the trait method
        $this->createJobs($this->getDefaultJobNames(), $this->getDefaultJobContent());
    }
}
