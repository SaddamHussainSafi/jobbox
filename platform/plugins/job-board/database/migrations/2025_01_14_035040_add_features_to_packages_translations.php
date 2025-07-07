<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (! Schema::hasColumn('jb_packages_translations', 'features')) {
            Schema::table('jb_packages_translations', function (Blueprint $table): void {
                $table->text('features')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('jb_packages_translations', 'features')) {
            Schema::table('jb_packages_translations', function (Blueprint $table): void {
                $table->dropColumn('features');
            });
        }
    }
};
