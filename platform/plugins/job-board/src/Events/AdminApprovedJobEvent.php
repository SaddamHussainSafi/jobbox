<?php

namespace Botble\JobBoard\Events;

use Botble\JobBoard\Models\Job;
use Illuminate\Foundation\Events\Dispatchable;

class AdminApprovedJobEvent
{
    use Dispatchable;

    public function __construct(public Job $job)
    {
    }
}
