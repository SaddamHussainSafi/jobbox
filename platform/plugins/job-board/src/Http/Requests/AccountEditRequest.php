<?php

namespace Botble\JobBoard\Http\Requests;

use Botble\Base\Rules\OnOffRule;
use Botble\Support\Http\Requests\Request;

class AccountEditRequest extends Request
{
    public function rules(): array
    {
        $rules = [
            'first_name' => 'required|max:120|min:2',
            'last_name' => 'required|max:120|min:2',
            'confirmed_at' => new OnOffRule(),
            'email' => 'required|max:60|min:6|email|unique:jb_accounts,email,' . $this->route('account.id'),
            'unique_id' => 'nullable|string|unique:jb_accounts,unique_id,' . $this->route('account.id'),
        ];

        if ($this->input('is_change_password') == 1) {
            $rules['password'] = 'required|min:6|confirmed';
        }

        return $rules;
    }
}
