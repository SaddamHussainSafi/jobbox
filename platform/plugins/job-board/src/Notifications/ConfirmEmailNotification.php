<?php

namespace Botble\JobBoard\Notifications;

use Botble\Base\Facades\EmailHandler;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\HtmlString;

class ConfirmEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $emailHandler = EmailHandler::setModule(JOB_BOARD_MODULE_SCREEN_NAME)
            ->setType('plugins')
            ->setTemplate('confirm-email')
            ->addTemplateSettings(JOB_BOARD_MODULE_SCREEN_NAME, config('plugins.job-board.email', []))
            ->setVariableValue(
                'verify_link',
                URL::signedRoute('public.account.confirm', [
                    'email' => urlencode($notifiable->email),
                ])
            );

        return (new MailMessage())
            ->view(['html' => new HtmlString($emailHandler->getContent())])
            ->subject($emailHandler->getSubject());
    }
}
