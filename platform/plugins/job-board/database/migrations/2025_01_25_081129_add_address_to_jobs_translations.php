<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (! Schema::hasColumn('jb_jobs_translations', 'features')) {
            Schema::table('jb_jobs_translations', function (Blueprint $table): void {
                $table->text('address')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('jb_jobs_translations', 'features')) {
            Schema::table('jb_jobs_translations', function (Blueprint $table): void {
                $table->dropColumn('address');
            });
        }
    }
};
