<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('jb_custom_fields', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->integer('order')->default(999);
            $table->boolean('is_global')->default(false);
            $table->nullableMorphs('authorable');
            $table->timestamps();
        });

        Schema::create('jb_custom_field_options', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('custom_field_id');
            $table->string('label')->nullable();
            $table->string('value');
            $table->integer('order')->default(999);
            $table->timestamps();
        });

        Schema::create('jb_custom_field_values', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->nullable();
            $table->string('value')->nullable();
            $table->morphs('reference');
            $table->foreignId('custom_field_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jb_custom_fields');
        Schema::dropIfExists('jb_custom_field_values');
        Schema::dropIfExists('jb_custom_field_options');
    }
};
