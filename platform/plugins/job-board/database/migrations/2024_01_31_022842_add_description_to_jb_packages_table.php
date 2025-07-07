<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('jb_packages', function (Blueprint $table): void {
            $table->string('description', 400)->nullable();
        });

        Schema::table('jb_packages_translations', function (Blueprint $table): void {
            $table->string('description', 400)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('jb_packages', function (Blueprint $table): void {
            $table->dropColumn('description');
        });

        Schema::table('jb_packages_translations', function (Blueprint $table): void {
            $table->dropColumn('description');
        });
    }
};
