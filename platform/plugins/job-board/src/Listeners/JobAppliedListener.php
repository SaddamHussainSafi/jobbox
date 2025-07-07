<?php

namespace Botble\JobBoard\Listeners;

use Botble\Base\Facades\EmailHandler;
use Botble\JobBoard\Events\JobAppliedEvent;
use Botble\Media\Facades\RvMedia;

class JobAppliedListener
{
    public function handle(JobAppliedEvent $event)
    {
        $job = $event->job;
        $jobApplication = $event->jobApplication;

        $employerEmails = array_filter($job->employer_emails ?: []);

        $emailHandler = EmailHandler::setModule(JOB_BOARD_MODULE_SCREEN_NAME)
            ->setVariableValues([
                'job_application_name' => $jobApplication->full_name,
                'job_application_position' => $jobApplication->job->name ?? null,
                'job_application_email' => $jobApplication->email ?? null,
                'job_application_phone' => $jobApplication->phone ?? null,
                'job_application_summary' => $jobApplication->message ? strip_tags(
                    $jobApplication->message
                ) : null,
                'job_application_resume' => $jobApplication->resume ? RvMedia::url(
                    $jobApplication->resume
                ) : null,
                'job_application_cover_letter' => $jobApplication->cover_letter ? RvMedia::url(
                    $jobApplication->cover_letter
                ) : null,
                'job_application' => $jobApplication,
                'job_name' => $job->name,
                'company_name' => $job->company->name ?? null,
            ]);

        $data = [
            'attachments' => $jobApplication->resume ? RvMedia::getRealPath($jobApplication->resume) : '',
        ];

        if (count($employerEmails)) {
            $emailHandler->sendUsingTemplate('employer-new-job-application', $employerEmails, $data);
        }

        $emailHandler->sendUsingTemplate('admin-new-job-application', null, $data);
        $emailHandler->sendUsingTemplate('job-seeker-applied-job', $jobApplication->email);
    }
}
