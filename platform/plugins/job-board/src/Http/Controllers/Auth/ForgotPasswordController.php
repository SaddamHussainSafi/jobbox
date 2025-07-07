<?php

namespace Botble\JobBoard\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Botble\ACL\Traits\SendsPasswordResetEmails;
use Botble\JobBoard\Forms\Fronts\Auth\ForgotPasswordForm;
use Botble\JobBoard\Http\Requests\Fronts\Auth\ForgotPasswordRequest;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Theme\Facades\Theme;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    public function showLinkRequestForm()
    {
        SeoHelper::setTitle(__('Forgot Password'));

        Theme::breadcrumb()->add(__('Forgot Password'), route('public.account.register'));

        return Theme::scope('job-board.auth.passwords.email', ['form' => ForgotPasswordForm::create()], 'plugins/job-board::themes.auth.passwords.email')->render();
    }

    public function sendResetLinkEmail(ForgotPasswordRequest $request)
    {
        $this->validateEmail($request);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->broker()->sendResetLink(
            $this->credentials($request)
        );

        return $response == Password::RESET_LINK_SENT
            ? $this->sendResetLinkResponse($request, $response)
            : $this->sendResetLinkFailedResponse($request, $response);
    }

    public function broker()
    {
        return Password::broker('accounts');
    }
}
