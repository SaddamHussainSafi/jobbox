<?php

namespace Botble\JobBoard\Http\Requests;

use Botble\Support\Http\Requests\Request;

class UploadResumeRequest extends Request
{
    public function rules(): array
    {
        return [
            'file' => ['required', 'mimes:pdf,doc,docx,ppt,pptx'],
        ];
    }
}
