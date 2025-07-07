<?php

namespace Botble\JobBoard\Models;

use Botble\Base\Models\BaseModel;
use Botble\JobBoard\Enums\InvoiceStatusEnum;
use Botble\Payment\Models\Payment;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Invoice extends BaseModel
{
    protected $table = 'jb_invoices';

    protected $fillable = [
        'code',
        'reference_id',
        'reference_type',
        'customer_name',
        'company_name',
        'company_logo',
        'tax_id',
        'customer_email',
        'customer_phone',
        'customer_address',
        'sub_total',
        'tax_amount',
        'shipping_amount',
        'discount_amount',
        'amount',
        'payment_id',
        'status',
        'paid_at',
        'coupon_code',
    ];

    protected $casts = [
        'sub_total' => 'float',
        'tax_amount' => 'float',
        'shipping_amount' => 'float',
        'discount_amount' => 'float',
        'amount' => 'float',
        'status' => InvoiceStatusEnum::class,
        'paid_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Invoice $invoice): void {
            $invoice->code = static::generateUniqueCode();
        });
    }

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public static function generateUniqueCode(): string
    {
        $prefix = setting('job_board_invoice_code_prefix', 'INV-');
        $nextInsertId = static::query()->max('id') + 1;

        do {
            $code = sprintf('%s%d', $prefix, $nextInsertId);
            $nextInsertId++;
        } while (static::query()->where('code', $code)->exists());

        return $code;
    }
}
