<?php

namespace Botble\JobBoard\Http\Requests;

use Botble\JobBoard\Enums\AccountTypeEnum;
use Botble\JobBoard\Models\Account;
use Botble\JobBoard\Models\Company;
use Botble\Support\Http\Requests\Request;
use Illuminate\Database\Query\Builder;
use Illuminate\Validation\Rule;

class StoreReviewRequest extends Request
{
    public function rules(): array
    {
        return [
            'reviewable_type' => ['required', Rule::in([Company::class, Account::class])],
            'reviewable_id' => [
                'required',
                Rule::exists($this->input('reviewable_type'), 'id')->where(function (Builder $query): void {
                    if ($this->input('reviewable_type') === Account::class) {
                        $query->where('type', AccountTypeEnum::JOB_SEEKER);
                    }
                }),
            ],
            'star' => ['required', 'numeric', 'min:1', 'max:5'],
            'review' => ['required', 'min:6', 'max:1000'],
        ];
    }
}
