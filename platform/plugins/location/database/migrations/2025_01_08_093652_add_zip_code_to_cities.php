<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('cities', function (Blueprint $table): void {
            $table->string('zip_code', 20)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('cities', function (Blueprint $table): void {
            $table->dropColumn('zip_code');
        });
    }
};
