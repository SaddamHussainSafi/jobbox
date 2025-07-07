<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jb_accounts', function (Blueprint $table): void {
            $table->string('unique_id')->nullable()->unique()->after('id');
        });

        Schema::table('jb_companies', function (Blueprint $table): void {
            $table->string('unique_id')->nullable()->unique()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('jb_companies', function (Blueprint $table): void {
            $table->dropColumn('unique_id');
        });

        Schema::table('jb_accounts', function (Blueprint $table): void {
            $table->dropColumn('unique_id');
        });
    }
};
