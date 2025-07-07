<?php

namespace Botble\JobBoard\Http\Resources;

use Botble\JobBoard\Models\Transaction;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Transaction
 */
class TransactionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->payment->amount,
            'description' => $this->getDescription(),
        ];
    }
}
