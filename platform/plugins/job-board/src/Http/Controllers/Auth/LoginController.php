<?php

namespace Botble\JobBoard\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Botble\ACL\Traits\AuthenticatesUsers;
use Botble\ACL\Traits\LogoutGuardTrait;
use Botble\JobBoard\Forms\Fronts\Auth\LoginForm;
use Botble\JobBoard\Http\Requests\Fronts\Auth\LoginRequest;
use Botble\JsValidation\Facades\JsValidator;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Theme\Facades\Theme;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use AuthenticatesUsers, LogoutGuardTrait {
        AuthenticatesUsers::attemptLogin as baseAttemptLogin;
    }

    public string $redirectTo = '/';

    public function showLoginForm()
    {
        SeoHelper::setTitle(__('Login'));

        Theme::breadcrumb()->add(__('Login'), route('public.account.register'));

        if (! session()->has('url.intended')) {
            session(['url.intended' => url()->previous()]);
        }

        Theme::asset()->container('footer')->add('js-validation', 'vendor/core/core/js-validation/js/js-validation.js', ['jquery']);
        Theme::asset()->container('footer')
            ->writeContent('js-validation-scripts', JsValidator::formRequest(LoginRequest::class), ['jquery']);

        return Theme::scope('job-board.auth.login', ['form' => LoginForm::create()], 'plugins/job-board::themes.auth.login')->render();
    }

    protected function guard()
    {
        return auth('account');
    }

    public function login(LoginRequest $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            if (auth('account')->user()->isEmployer()) {
                $this->redirectTo = route('public.account.dashboard');
            }

            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to log in and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse();
    }

    protected function attemptLogin(Request $request)
    {
        if ($this->guard()->validate($this->credentials($request))) {
            $account = $this->guard()->getLastAttempted();

            if (setting('verify_account_email', 0) && empty($account->confirmed_at)) {
                throw ValidationException::withMessages([
                    'confirmation' => [
                        trans('plugins/job-board::account.not_confirmed', [
                            'resend_link' => route(
                                'public.account.resend_confirmation',
                                ['email' => $account->email]
                            ),
                        ]),
                    ],
                ]);
            }

            return $this->baseAttemptLogin($request);
        }

        return false;
    }

    public function logout(Request $request)
    {
        $activeGuards = 0;
        $this->guard()->logout();

        foreach (config('auth.guards', []) as $guard => $guardConfig) {
            if ($guardConfig['driver'] !== 'session') {
                continue;
            }
            if ($this->isActiveGuard($request, $guard)) {
                $activeGuards++;
            }
        }

        if (! $activeGuards) {
            $request->session()->flush();
            $request->session()->regenerate();
        }

        $this->loggedOut($request);

        return redirect()->to(route('public.index'));
    }
}
