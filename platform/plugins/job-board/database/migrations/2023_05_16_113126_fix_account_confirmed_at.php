<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class () extends Migration {
    public function up(): void
    {
        if (! setting('verify_account_email', 0)) {
            DB::table('jb_accounts')->update(['confirmed_at' => Carbon::now()]);
        }
    }
};
