<?php

namespace Botble\JobBoard\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Botble\ACL\Traits\ResetsPasswords;
use Botble\JobBoard\Forms\Fronts\Auth\ResetPasswordForm;
use Botble\JobBoard\Http\Requests\Fronts\Auth\ResetPasswordRequest;
use Botble\JobBoard\Models\Account;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Theme\Facades\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    public string $redirectTo = '/';

    public function __construct()
    {
        $this->redirectTo = route('public.account.dashboard');
    }

    public function showResetForm(Request $request, $token = null)
    {
        SeoHelper::setTitle(__('Reset Password'));

        Theme::breadcrumb()->add(
            __('Reset Password'),
            route('public.account.register')
        );

        return Theme::scope(
            'job-board.auth.passwords.reset',
            [
                'token' => $token,
                'email' => $request->input('email'),
                'form' => ResetPasswordForm::create(),
            ],
            'plugins/job-board::themes.auth.passwords.reset'
        )->render();
    }

    public function reset(ResetPasswordRequest $request)
    {
        $request->validate($this->rules(), $this->validationErrorMessages());

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise, we will parse the error and return the response.
        $response = $this->broker()->reset($this->credentials($request), function ($user, $password): void {
            $this->resetPassword($user, $password);
        });

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $response == Password::PASSWORD_RESET
            ? $this->sendResetResponse($request, $response)
            : $this->sendResetFailedResponse($request, $response);
    }

    public function redirectTo()
    {
        /**
         * @var Account $account
         */
        $account = request()->user('account');

        if (! $account->isEmployer()) {
            $this->redirectTo = route('public.index');
        }

        return $this->redirectTo;
    }

    public function broker()
    {
        return Password::broker('accounts');
    }

    protected function guard()
    {
        return auth('account');
    }
}
