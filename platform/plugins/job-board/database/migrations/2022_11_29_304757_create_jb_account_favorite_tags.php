<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('jb_account_favorite_tags', function (Blueprint $table): void {
            $table->unsignedBigInteger('tag_id');
            $table->unsignedBigInteger('account_id');
            $table->primary([
                'tag_id',
                'account_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jb_account_favorite_tags');
    }
};
