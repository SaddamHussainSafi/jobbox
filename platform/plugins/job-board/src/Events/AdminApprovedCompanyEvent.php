<?php

namespace Botble\JobBoard\Events;

use Botble\JobBoard\Models\Company;
use Illuminate\Foundation\Events\Dispatchable;

class AdminApprovedCompanyEvent
{
    use Dispatchable;

    public function __construct(public Company $company)
    {
    }
}
