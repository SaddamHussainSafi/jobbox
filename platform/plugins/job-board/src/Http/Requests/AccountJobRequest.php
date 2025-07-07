<?php

namespace Botble\JobBoard\Http\Requests;

use Botble\JobBoard\Models\Account;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class AccountJobRequest extends JobRequest
{
    public function rules(): array
    {
        /**
         * @var Account $account
         */
        $account = auth('account')->user();
        $companyIds = $account->companies->pluck('id')->all();

        $rules = parent::rules();
        Arr::forget($rules, 'moderation_status');

        return array_merge($rules, [
            'company_id' => [
                'required',
                Rule::in(array_values($companyIds)),
            ],
        ]);
    }

    public function messages(): array
    {
        return [
            'company_id.required' => __('You must add at least 1 company first.'),
        ];
    }
}
