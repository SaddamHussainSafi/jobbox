<?php

namespace Botble\JobBoard\Listeners;

use Botble\Base\Events\AdminNotificationEvent;
use Botble\Base\Supports\AdminNotificationItem;
use Botble\JobBoard\Events\JobAppliedEvent;

class NewApplicationNotification
{
    public function handle(JobAppliedEvent $event): void
    {
        event(new AdminNotificationEvent(
            AdminNotificationItem::make()
                ->title(trans('plugins/job-board::job-application.notifications.title'))
                ->description(trans('plugins/job-board::job-application.notifications.description', [
                    'name' => $event->jobApplication->full_name,
                ]))
                ->action(trans('plugins/job-board::job-application.notifications.view'), route('job-applications.edit', $event->jobApplication))
        ));
    }
}
