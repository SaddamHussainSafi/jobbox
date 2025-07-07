<?php

use Botble\JobBoard\Models\Job;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('jb_jobs_types')) {
            Schema::create('jb_jobs_types', function (Blueprint $table): void {
                $table->foreignId('job_id');
                $table->foreignId('job_type_id');
            });

            $jobs = Job::get();

            foreach ($jobs as $job) {
                $job->jobTypes()->attach($job->job_type_id);
            }

            Schema::table('jb_jobs', function (Blueprint $table): void {
                $table->dropColumn('job_type_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('jb_jobs_types');
    }
};
