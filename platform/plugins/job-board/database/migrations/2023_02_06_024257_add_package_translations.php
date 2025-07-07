<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('jb_packages_translations')) {
            Schema::create('jb_packages_translations', function (Blueprint $table): void {
                $table->string('lang_code');
                $table->foreignId('jb_packages_id');
                $table->string('name')->nullable();

                $table->primary(['lang_code', 'jb_packages_id'], 'jb_packages_translations_primary');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('jb_packages_translations');
    }
};
