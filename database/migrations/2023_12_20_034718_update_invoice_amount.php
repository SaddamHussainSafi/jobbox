<?php

use Botble\JobBoard\Models\Invoice;
use Botble\JobBoard\Models\InvoiceItem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class() extends Migration
{
    public function up(): void
    {
        try {
            DB::transaction(function () {
                Invoice::query()
                    ->where('discount_amount', '>', 0)
                    ->whereRaw('sub_total = amount')
                    ->update([
                        'amount' => DB::raw('sub_total + discount_amount'),
                    ]);

                InvoiceItem::query()
                    ->where('discount_amount', '>', 0)
                    ->whereRaw('sub_total = amount')
                    ->update([
                        'amount' => DB::raw('sub_total + discount_amount'),
                    ]);
            });
        } catch (Throwable) {}
    }
};
