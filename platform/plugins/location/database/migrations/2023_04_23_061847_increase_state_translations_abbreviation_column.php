<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('states_translations', function (Blueprint $table): void {
            $table->string('abbreviation', 10)->nullable()->change();
        });

        Schema::table('states', function (Blueprint $table): void {
            $table->string('abbreviation', 10)->nullable()->change();
        });
    }
};
