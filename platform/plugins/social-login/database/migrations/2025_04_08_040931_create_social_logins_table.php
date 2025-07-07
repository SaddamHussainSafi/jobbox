<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('social_logins', function (Blueprint $table): void {
            $table->id();
            $table->morphs('user'); // This will create user_id and user_type columns
            $table->string('provider');
            $table->string('provider_id');
            $table->text('token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->timestamp('token_expires_at')->nullable();
            $table->json('provider_data')->nullable(); // Store additional provider-specific data
            $table->timestamps();

            $table->unique(['provider', 'provider_id']);
            $table->index(['user_id', 'user_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_logins');
    }
};
