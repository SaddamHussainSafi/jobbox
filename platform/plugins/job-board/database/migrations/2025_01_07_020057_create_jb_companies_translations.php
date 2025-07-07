<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('jb_companies_translations', function (Blueprint $table) {
            $table->string('lang_code');
            $table->foreignId('jb_companies_id');
            $table->string('name')->nullable();
            $table->string('description', 400)->nullable();
            $table->longText('content')->nullable();

            $table->primary(['lang_code', 'jb_companies_id'], 'jb_companies_translations_translations_primary');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jb_companies_translations');
    }
};
