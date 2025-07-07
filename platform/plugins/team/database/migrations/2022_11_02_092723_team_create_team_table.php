<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('teams', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('photo')->nullable();
            $table->string('title')->nullable();
            $table->string('location')->nullable();
            $table->string('socials')->nullable();
            $table->string('status', 60)->default('published');

            $table->timestamps();
        });

        Schema::create('teams_translations', function (Blueprint $table): void {
            $table->string('lang_code');
            $table->integer('teams_id');
            $table->string('name');
            $table->string('title')->nullable();
            $table->string('location')->nullable();

            $table->primary(['lang_code', 'teams_id'], 'teams_translations_primary');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teams');
        Schema::dropIfExists('teams_translations');
    }
};
