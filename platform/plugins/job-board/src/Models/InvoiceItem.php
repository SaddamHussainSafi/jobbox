<?php

namespace Botble\JobBoard\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class InvoiceItem extends BaseModel
{
    protected $table = 'jb_invoice_items';

    protected $fillable = [
        'invoice_id',
        'reference_type',
        'reference_id',
        'name',
        'description',
        'image',
        'qty',
        'sub_total',
        'tax_amount',
        'discount_amount',
        'amount',
        'metadata',
    ];

    protected $casts = [
        'sub_total' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'amount' => 'decimal:2',
        'metadata' => 'json',
        'paid_at' => 'datetime',
        'name' => SafeContent::class,
        'description' => SafeContent::class,
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }
}
