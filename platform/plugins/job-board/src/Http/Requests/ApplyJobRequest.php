<?php

namespace Botble\JobBoard\Http\Requests;

use Botble\Base\Facades\BaseHelper;
use Botble\Captcha\Facades\Captcha;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class ApplyJobRequest extends Request
{
    public function rules(): array
    {
        $rules = [
            'job_type' => Rule::in(['internal', 'external']),
            'email' => 'required|email',
            'message' => 'nullable|max:1000',
        ];

        if ($this->input('job_type') === 'internal') {
            $rules = array_merge($rules, [
                'first_name' => 'required|max:120|min:2',
                'last_name' => 'required|max:120|min:2',
                'resume' => 'nullable|file',
                'cover_letter' => 'nullable|file',
                'message' => 'nullable|max:1000',
                'phone' => 'nullable|' . BaseHelper::getPhoneValidationRule(),
            ]);
        }

        if (
            is_plugin_active('captcha') &&
            setting('enable_captcha') &&
            setting('job_board_enable_recaptcha_in_apply_job', 0)
        ) {
            $rules += Captcha::rules();
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'first_name' => __('First Name'),
            'last_name' => __('Last Name'),
            'email' => __('Email'),
            'phone' => __('Phone'),
            'message' => __('Message'),
            'resume' => __('Resume'),
            'cover_letter' => __('Cover letter'),
        ] + (is_plugin_active('captcha') ? Captcha::attributes() : []);
    }
}
