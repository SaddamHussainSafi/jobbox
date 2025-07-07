<?php

namespace Botble\JobBoard\Http\Resources;

use Botble\JobBoard\Models\Account;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Account
 */
class AccountResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name . ($this->email ? ' - ' . $this->email : ''),
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'avatar' => $this->avatar_url,
            'dob' => $this->dob,
            'gender' => $this->gender,
            'description' => $this->description,
            'credits' => $this->credits,
        ];
    }
}
