<?php

use Botble\Base\Supports\Database\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('jb_accounts', function (Blueprint $table): void {
            $table->boolean('hide_cv')->default(false)->after('is_public_profile');
        });
    }

    public function down(): void
    {
        Schema::table('jb_accounts', function (Blueprint $table): void {
            $table->dropColumn('hide_cv');
        });
    }
};
