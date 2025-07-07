<?php

namespace Botble\JobBoard\Http\Requests;

use Botble\Support\Http\Requests\Request;

class CreateTransactionRequest extends Request
{
    public function rules(): array
    {
        return [
            'credits' => ['required', 'numeric', 'min:1'],
        ];
    }
}
