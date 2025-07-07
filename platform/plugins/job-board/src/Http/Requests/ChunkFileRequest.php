<?php

namespace Botble\JobBoard\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChunkFileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'file' => 'required|string',
        ];
    }
}
