<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('jb_tags', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 120);
            $table->string('description', 400)->nullable();
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });

        Schema::create('jb_jobs_tags', function (Blueprint $table): void {
            $table->foreignId('job_id')->index();
            $table->foreignId('tag_id')->index();

            $table->primary(['job_id', 'tag_id']);
        });

        Schema::create('jb_tags_translations', function (Blueprint $table): void {
            $table->string('lang_code');
            $table->foreignId('jb_tags_id');
            $table->string('name')->nullable();

            $table->primary(['lang_code', 'jb_tags_id'], 'jb_tags_translations_primary');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jb_tags');
        Schema::dropIfExists('jb_jobs_tags');
        Schema::dropIfExists('jb_tags_translations');
    }
};
