<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('payment_logs')) {
            return;
        }

        Schema::create('payment_logs', function (Blueprint $table): void {
            $table->id();
            $table->string('payment_method');
            $table->longText('request')->nullable();
            $table->longText('response')->nullable();
            $table->ipAddress();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_logs');
    }
};
