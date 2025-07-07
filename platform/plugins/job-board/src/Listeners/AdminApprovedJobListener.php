<?php

namespace Botble\JobBoard\Listeners;

use Botble\Base\Facades\EmailHandler;
use Botble\JobBoard\Events\AdminApprovedJobEvent;

class AdminApprovedJobListener
{
    public function handle(AdminApprovedJobEvent $event): void
    {
        $mailer = EmailHandler::setModule(JOB_BOARD_MODULE_SCREEN_NAME)
            ->setType('plugins')
            ->setVariableValues([
                'job_name' => $event->job->name,
                'job_url' => $event->job->url,
                'job_author' => $event->job->author?->name,
            ]);

        $author = $event->job->author;

        if ($mailer->templateEnabled('job-approved') && $author) {
            $mailer->sendUsingTemplate('job-approved', $author->email);
        }
    }
}
