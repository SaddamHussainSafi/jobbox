<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (Schema::hasColumn('payments', 'metadata')) {
            return;
        }

        Schema::table('payments', function (Blueprint $table): void {
            $table->text('metadata')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table): void {
            if (Schema::hasColumn('payments', 'metadata')) {
                $table->dropColumn('metadata');
            }
        });
    }
};
