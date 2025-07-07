<?php

namespace Botble\JobBoard\Notifications;

use Botble\Base\Facades\EmailHandler;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class ResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public string $token)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $emailHandler = EmailHandler::setModule(JOB_BOARD_MODULE_SCREEN_NAME)
            ->setType('plugins')
            ->setTemplate('password-reminder')
            ->addTemplateSettings(JOB_BOARD_MODULE_SCREEN_NAME, config('plugins.job-board.email', []))
            ->setVariableValue('reset_link', route('public.account.password.reset', ['token' => $this->token, 'email' => request()->input('email')]));

        return (new MailMessage())
            ->view(['html' => new HtmlString($emailHandler->getContent())])
            ->subject($emailHandler->getSubject());
    }
}
