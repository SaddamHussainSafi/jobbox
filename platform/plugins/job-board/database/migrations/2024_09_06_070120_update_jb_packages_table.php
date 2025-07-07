<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jb_packages', function (Blueprint $table): void {
            $table->text('features')->nullable()->after('is_default');
        });
    }

    public function down(): void
    {
        Schema::table('jb_packages', function (Blueprint $table): void {
            $table->dropColumn('features');
        });
    }
};
