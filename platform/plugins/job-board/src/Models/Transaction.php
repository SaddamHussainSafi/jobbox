<?php

namespace Botble\JobBoard\Models;

use Botble\ACL\Models\User;
use Botble\Base\Casts\SafeContent;
use Botble\Base\Facades\Html;
use Botble\Base\Models\BaseModel;
use Botble\Payment\Models\Payment;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends BaseModel
{
    protected $table = 'jb_transactions';

    protected $fillable = [
        'credits',
        'description',
        'user_id',
        'account_id',
        'payment_id',
    ];

    protected $casts = [
        'description' => SafeContent::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class)->withDefault();
    }

    public function getDescription(): string
    {
        if ($this->user_id) {
            return __('Added :credits credit(s) by admin ":name"', ['credits' => $this->credits, 'name' => $this->user->name]);
        }

        $time = Html::tag('span', $this->created_at->diffForHumans(), ['class' => 'small italic']);

        $description = __('You have purchased :credits credit(s)', ['credits' => $this->credits]);
        if ($this->payment_id) {
            $description .= __(' via :payment', ['payment' => $this->payment->payment_channel->label()]);
            $description .= ': ' . number_format($this->payment->amount, 2) . $this->payment->currency;
        }

        return $description . ' - ' . $time;
    }
}
