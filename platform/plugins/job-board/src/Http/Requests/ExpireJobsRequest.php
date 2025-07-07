<?php

namespace Botble\JobBoard\Http\Requests;

use Botble\Support\Http\Requests\Request;

class ExpireJobsRequest extends Request
{
    public function rules(): array
    {
        return [
            'ids' => ['required', 'array'],
        ];
    }
}
