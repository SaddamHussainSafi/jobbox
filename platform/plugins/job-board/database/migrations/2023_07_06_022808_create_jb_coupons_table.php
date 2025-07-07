<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('jb_coupons', function (Blueprint $table): void {
            $table->id();
            $table->string('type');
            $table->string('code', 20)->unique();
            $table->decimal('value');
            $table->integer('quantity')->nullable();
            $table->unsignedInteger('total_used')->default(0);
            $table->dateTime('expires_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jb_coupons');
    }
};
