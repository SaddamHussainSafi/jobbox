<?php

namespace Botble\JobBoard\Listeners;

use Botble\Base\Facades\EmailHandler;
use Botble\JobBoard\Events\EmployerPostedJobEvent;

class EmployerPostedJobListener
{
    public function handle(EmployerPostedJobEvent $event): void
    {
        $mailer = EmailHandler::setModule(JOB_BOARD_MODULE_SCREEN_NAME)
            ->setType('plugins')
            ->setVariableValues([
                'job_name' => $event->job->name,
                'job_url' => route('jobs.edit', $event->job->id),
                'job_author' => $event->job->author?->name,
            ]);

        $mailer->sendUsingTemplate('new-job-posted', get_admin_email()->toArray());
    }
}
