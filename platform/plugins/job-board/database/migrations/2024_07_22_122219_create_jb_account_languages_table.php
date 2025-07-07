<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jb_account_languages', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('account_id');
            $table->foreignId('language_level_id');
            $table->string('language', 10);
            $table->boolean('is_native')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jb_account_languages');
    }
};
