<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (! Schema::hasColumn('teams', 'phone')) {
            Schema::table('teams', function (Blueprint $table): void {
                $table->longText('content')->nullable();
                $table->string('phone', 15)->nullable();
                $table->string('email')->nullable();
                $table->string('address')->nullable();
                $table->string('website')->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::table('teams', function (Blueprint $table): void {;
            $table->dropColumn('website');
            $table->dropColumn('address');
            $table->dropColumn('email');
            $table->dropColumn('phone');
            $table->dropColumn('content');
        });
    }
};
