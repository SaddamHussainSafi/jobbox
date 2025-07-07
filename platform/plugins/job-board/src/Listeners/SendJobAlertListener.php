<?php

namespace Botble\JobBoard\Listeners;

use Botble\Base\Facades\EmailHandler;
use Botble\JobBoard\Enums\AccountTypeEnum;
use Botble\JobBoard\Events\JobPublishedEvent;
use Botble\JobBoard\Models\Account;

class SendJobAlertListener
{
    public function handle(JobPublishedEvent $event): void
    {
        $job = $event->job;
        $job->loadMissing(['skills', 'tags', 'company']);

        $tagIds = $job->tags->pluck('id')->all();
        $skillIds = $job->skills->pluck('id')->all();

        $accounts = Account::query()
            ->where('type', AccountTypeEnum::JOB_SEEKER)
            ->where(function ($query) use ($tagIds, $skillIds): void {
                $query
                    ->whereHas('favoriteTags', function ($query) use ($tagIds): void {
                        $query->whereIn('jb_account_favorite_tags.tag_id', $tagIds);
                    })
                    ->orWhereHas('favoriteSkills', function ($query) use ($skillIds): void {
                        $query->whereIn('jb_account_favorite_skills.skill_id', $skillIds);
                    });
            })
            ->get();

        foreach ($accounts as $account) {
            EmailHandler::setModule(JOB_BOARD_MODULE_SCREEN_NAME)
                ->setVariableValues([
                    'job_name' => $job->name,
                    'job_url' => $job->url,
                    'company_name' => $job->hide_company ? $job->company->name : '',
                    'account_name' => $account->name,
                ])
                ->sendUsingTemplate('job-seeker-job-alert', $account->email);
        }
    }
}
