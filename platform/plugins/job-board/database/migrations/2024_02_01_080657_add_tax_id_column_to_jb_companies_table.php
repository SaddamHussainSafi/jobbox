<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('jb_companies', function (Blueprint $table): void {
            $table->string('tax_id', 60)->nullable();
        });

        Schema::table('jb_invoices', function (Blueprint $table): void {
            $table->renameColumn('customer_tax_id', 'tax_id');
        });
    }

    public function down(): void
    {
        Schema::table('jb_companies', function (Blueprint $table): void {
            $table->dropColumn('tax_id');
        });

        Schema::table('jb_invoices', function (Blueprint $table): void {
            $table->dropColumn('tax_id', 'customer_tax_id');
        });
    }
};
