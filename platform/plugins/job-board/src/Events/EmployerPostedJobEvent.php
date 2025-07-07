<?php

namespace Botble\JobBoard\Events;

use Botble\JobBoard\Models\Account;
use Botble\JobBoard\Models\Job;
use Illuminate\Foundation\Events\Dispatchable;

class EmployerPostedJobEvent
{
    use Dispatchable;

    public function __construct(public Job $job, Account $account)
    {
    }
}
