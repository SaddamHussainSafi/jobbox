<?php

use Botble\Base\Supports\Database\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('jb_categories', function (Blueprint $table): void {
            $table->foreignId('parent_id')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('jb_categories', function (Blueprint $table): void {
            $table->dropColumn('parent_id');
        });
    }
};
