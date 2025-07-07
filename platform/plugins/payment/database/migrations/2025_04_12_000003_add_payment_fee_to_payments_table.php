<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (! Schema::hasColumn('payments', 'payment_fee')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->decimal('payment_fee', 15)->default(0)->nullable()->after('amount');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('payments', 'payment_fee')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->dropColumn('payment_fee');
            });
        }
    }
};
