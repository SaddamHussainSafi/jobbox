<?php

namespace Botble\JobBoard\Http\Requests;

use Botble\JobBoard\Enums\JobApplicationStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class EditJobApplicationRequest extends Request
{
    public function rules(): array
    {
        return [
            'status' => Rule::in(JobApplicationStatusEnum::values()),
        ];
    }
}
