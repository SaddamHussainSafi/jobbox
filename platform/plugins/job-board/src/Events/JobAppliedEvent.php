<?php

namespace Botble\JobBoard\Events;

use Botble\JobBoard\Models\Job;
use Botble\JobBoard\Models\JobApplication;
use Illuminate\Foundation\Events\Dispatchable;

class JobAppliedEvent
{
    use Dispatchable;

    public function __construct(public JobApplication $jobApplication, public Job $job)
    {
    }
}
