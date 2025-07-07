<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('jb_jobs', function (Blueprint $table): void {
            $table->text('apply_url')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('jb_jobs', function (Blueprint $table): void {
            $table->string('apply_url')->nullable()->change();
        });
    }
};
