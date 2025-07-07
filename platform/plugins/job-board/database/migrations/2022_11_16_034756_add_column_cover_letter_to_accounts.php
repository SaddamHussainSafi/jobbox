<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('jb_accounts', function (Blueprint $table): void {
            $table->string('cover_letter', 200)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('jb_accounts', function (Blueprint $table): void {
            $table->dropColumn('cover_letter');
        });
    }
};
