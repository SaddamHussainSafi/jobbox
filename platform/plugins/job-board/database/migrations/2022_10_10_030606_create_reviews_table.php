<?php

use Botble\Base\Enums\BaseStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('jb_reviews', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('account_id');
            $table->foreignId('company_id');
            $table->float('star');
            $table->text('review');
            $table->string('status', 60)->default(BaseStatusEnum::PUBLISHED);
            $table->timestamps();
            $table->index(['account_id', 'company_id', 'status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('js_reviews');
    }
};
