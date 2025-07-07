<?php

namespace Botble\JobBoard\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class DegreeLevelRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'order' => ['required', 'numeric', 'min:0'],
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
