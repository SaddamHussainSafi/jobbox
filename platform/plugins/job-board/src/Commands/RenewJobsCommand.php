<?php

namespace Botble\JobBoard\Commands;

use Botble\Base\Facades\EmailHandler;
use Botble\JobBoard\Facades\JobBoardHelper;
use Botble\JobBoard\Models\Account;
use Botble\JobBoard\Models\Job;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand('cms:jobs:renew', 'Renew jobs')]
class RenewJobsCommand extends Command
{
    public function handle(): int
    {
        // @phpstan-ignore-next-line
        $jobs = Job::query()
            ->expired()
            ->where(JobBoardHelper::getJobDisplayQueryConditions())
            ->where('author_type', Account::class)
            ->join('jb_accounts', 'jb_accounts.id', '=', 'jb_jobs.author_id')
            ->where('jb_accounts.credits', '>', 0)
            ->where('jb_jobs.auto_renew', 1)
            ->with(['author'])
            ->select('jb_jobs.*')
            ->get();

        foreach ($jobs as $job) {
            if ($job->author->credits <= 0) {
                continue;
            }

            $job->expire_date = Carbon::now()->addDays(JobBoardHelper::jobExpiredDays());
            $job->save();

            if (JobBoardHelper::isEnabledCreditsSystem() && $job->author->credits > 0) {
                $job->author->credits--;
                $job->author->save();
            }

            EmailHandler::setModule(JOB_BOARD_MODULE_SCREEN_NAME)
                ->setVariableValues([
                    'job_name' => $job->name,
                    'job_url' => $job->url,
                    'job_author' => $job->author->name,
                ])
                ->sendUsingTemplate('job-renewed', $job->employer_emails);
        }

        $this->components->info(sprintf('Renewed %s jobs successfully!', number_format($jobs->count())));

        return self::SUCCESS;
    }
}
