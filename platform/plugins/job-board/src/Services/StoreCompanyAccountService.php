<?php

namespace Botble\JobBoard\Services;

use Botble\JobBoard\Models\Account;
use Botble\JobBoard\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreCompanyAccountService
{
    public function execute(Request $request, Company $company): void
    {
        $accounts = DB::table('jb_companies_accounts')
            ->where('jb_companies_accounts.company_id', $company->getKey())
            ->join('jb_accounts', 'jb_accounts.id', '=', 'jb_companies_accounts.company_id')
            ->select(DB::raw('CONCAT(jb_accounts.first_name, " ", jb_accounts.last_name) as name'))
            ->pluck('name')
            ->all();

        $accountsInput = collect(json_decode($request->input('accounts'), true))->pluck('value')->all();

        if (count($accounts) != count($accountsInput) || count(array_diff($accounts, $accountsInput)) > 0) {
            DB::table('jb_companies_accounts')
                ->where('company_id', $company->getKey())
                ->delete();

            foreach ($accountsInput as $accountName) {
                if (! trim($accountName)) {
                    continue;
                }

                $account = Account::query()
                    ->where(DB::raw('CONCAT(jb_accounts.first_name, " ", jb_accounts.last_name)'), $accountName)
                    ->first();

                if (! empty($account)) {
                    DB::table('jb_companies_accounts')
                        ->where('jb_companies_accounts.company_id', $company->getKey())
                        ->insert([
                            'company_id' => $company->getKey(),
                            'account_id' => $account->getKey(),
                        ]);
                }
            }
        }
    }
}
