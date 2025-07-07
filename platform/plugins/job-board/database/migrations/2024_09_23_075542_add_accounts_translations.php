<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('jb_accounts_translations')) {
            Schema::create('jb_accounts_translations', function (Blueprint $table): void {
                $table->string('lang_code');
                $table->foreignId('jb_accounts_id');
                $table->string('first_name')->nullable();
                $table->string('last_name')->nullable();
                $table->string('description')->nullable();

                $table->primary(['lang_code', 'jb_accounts_id'], 'jb_accounts_translations_primary');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('jb_accounts_translations');
    }
};
