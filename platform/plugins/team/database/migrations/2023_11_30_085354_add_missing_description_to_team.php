<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('teams', 'description')) {
            Schema::table('teams', function (Blueprint $table): void {
                $table->string('description', 400)->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::table('team', function (Blueprint $table): void {
            $table->dropColumn('description');
        });
    }
};
