<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('jb_accounts', function (Blueprint $table): void {
            $table->boolean('available_for_hiring')->default(true);
            $table->foreignId('country_id')->default(1)->nullable();
            $table->foreignId('state_id')->nullable();
            $table->foreignId('city_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('jb_accounts', function (Blueprint $table): void {
            $table->dropColumn(['available_for_hiring', 'country_id', 'state_id', 'city_id']);
        });
    }
};
