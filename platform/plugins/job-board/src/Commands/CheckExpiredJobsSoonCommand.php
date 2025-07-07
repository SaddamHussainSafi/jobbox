<?php

namespace Botble\JobBoard\Commands;

use Botble\Base\Facades\EmailHandler;
use Botble\JobBoard\Models\Account;
use Botble\JobBoard\Models\Job;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand('cms:jobs:check-expired-soon', 'Check expired jobs will be expired in 3 days')]
class CheckExpiredJobsSoonCommand extends Command
{
    public function handle(): int
    {
        $this->components->info('Checking...');

        // @phpstan-ignore-next-line
        $jobs = Job::query()
            ->active()
            ->where('never_expired', false)
            ->where('author_type', Account::class)
            ->join('jb_accounts', 'jb_accounts.id', '=', 'jb_jobs.author_id')
            ->with(['author'])
            ->select('jb_jobs.*')
            ->get();

        $count = 0;
        foreach ($jobs as $job) {
            $expiredAfter = $job->expire_date->diffInDays(Carbon::now());
            if ($expiredAfter > 3) {
                continue;
            }

            $count++;

            EmailHandler::setModule(JOB_BOARD_MODULE_SCREEN_NAME)
                ->setVariableValues([
                    'job_name' => $job->name,
                    'job_url' => $job->url,
                    'job_author' => $job->author->name,
                    'job_list' => route('public.account.jobs.index'),
                    'job_expired_after' => $expiredAfter,
                ])
                ->sendUsingTemplate('job-expired-soon', $job->employer_emails);
        }

        $this->components->info(sprintf('%s jobs will be expired in next 3 days!', number_format($count)));

        return self::SUCCESS;
    }
}
