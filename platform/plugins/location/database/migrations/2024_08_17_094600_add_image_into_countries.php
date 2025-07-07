<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (! Schema::hasColumn('countries', 'image')) {
            Schema::table('countries', function (Blueprint $table): void {
                $table->string('image')->after('order')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('countries', 'image')) {
            Schema::table('countries', function (Blueprint $table): void {
                $table->dropColumn('image');
            });
        }
    }
};
