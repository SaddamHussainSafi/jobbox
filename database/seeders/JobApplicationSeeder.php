<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\JobBoard\Enums\AccountTypeEnum;
use Botble\JobBoard\Enums\JobApplicationStatusEnum;
use Botble\JobBoard\Models\Account;
use Botble\JobBoard\Models\Job;
use Botble\JobBoard\Models\JobApplication;

class JobApplicationSeeder extends BaseSeeder
{
    public function run(): void
    {
        JobApplication::query()->truncate();

        $faker = $this->fake();

        $accounts = Account::query()
            ->select(['id', 'first_name', 'last_name', 'phone', 'email', 'resume'])
            ->where('type', AccountTypeEnum::JOB_SEEKER)
            ->inRandomOrder()
            ->limit(20)
            ->get();

        $jobs = Job::query()
            ->select('id')
            ->inRandomOrder()
            ->limit(20)
            ->get();

        foreach ($accounts as $key => $account) {
            JobApplication::query()->create([
                'first_name' => $account->first_name,
                'last_name' => $account->last_name,
                'phone' => $account->phone,
                'email' => $account->email,
                'is_external_apply' => mt_rand(0, 1),
                'resume' => $account->resume,
                'cover_letter' => $account->resume,
                'message' => $faker->realText(),
                'job_id' => $jobs[$key]->id,
                'account_id' => $account->id,
                'status' => JobApplicationStatusEnum::CHECKED(),
            ]);
        }
    }
}
