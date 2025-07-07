<?php

namespace Botble\JobBoard\Http\Controllers\Auth;

use Botble\ACL\Traits\RegistersUsers;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Facades\EmailHandler;
use Botble\Base\Http\Controllers\BaseController;
use Botble\JobBoard\Enums\AccountTypeEnum;
use Botble\JobBoard\Forms\Fronts\Auth\RegisterForm;
use Botble\JobBoard\Http\Requests\Fronts\Auth\RegisterRequest;
use Botble\JobBoard\Models\Account;
use Botble\JsValidation\Facades\JsValidator;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Theme\Facades\Theme;
use Carbon\Carbon;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class RegisterController extends BaseController
{
    use RegistersUsers;

    protected string $redirectTo = '/';

    public function showRegistrationForm()
    {
        SeoHelper::setTitle(__('Register'));

        Theme::breadcrumb()->add(__('Register'), route('public.account.register'));

        Theme::asset()->container('footer')
            ->add('js-validation', 'vendor/core/core/js-validation/js/js-validation.js', ['jquery']);
        Theme::asset()->container('footer')->writeContent(
            'js-validation-scripts',
            JsValidator::formRequest(RegisterRequest::class),
            ['jquery']
        );

        if (! session()->has('url.intended')) {
            session(['url.intended' => url()->previous()]);
        }

        return Theme::scope(
            'job-board.auth.register',
            ['form' => RegisterForm::create()],
            'plugins/job-board::themes.auth.register'
        )->render();
    }

    public function confirm($email, Request $request)
    {
        abort_unless(URL::hasValidSignature($request), 404);

        $account = Account::query()
            ->where('email', $email)
            ->firstOrFail();

        $account->confirmed_at = Carbon::now();
        $account->save();

        $this->guard()->login($account);

        return $this
            ->httpResponse()
            ->setNextUrl(route('public.account.dashboard'))
            ->setMessage(trans('plugins/job-board::account.confirmation_successful'));
    }

    protected function guard()
    {
        return auth('account');
    }

    public function resendConfirmation(Request $request)
    {
        /**
         * @var Account $account
         */
        $account = Account::query()
            ->where('email', $request->input('email'))
            ->first();

        if (! $account) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('Cannot find this account!'));
        }

        try {
            $account->sendEmailVerificationNotification();
        } catch (Exception $exception) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage($exception->getMessage());
        }

        return $this
            ->httpResponse()
            ->setMessage(trans('plugins/job-board::account.confirmation_resent'));
    }

    public function register(RegisterRequest $request)
    {
        if ($request->input('is_employer') && setting('job_board_enabled_register_as_employer', 1)) {
            $request->merge(['type' => AccountTypeEnum::EMPLOYER]);
        } else {
            $request->merge(['type' => AccountTypeEnum::JOB_SEEKER]);
        }

        /**
         * @var Account $account
         */
        $account = $this->create($request->input());

        event(new Registered($account));

        $request->merge(['slug' => $account->name, 'is_slug_editable' => 1]);

        event(new CreatedContentEvent(ACCOUNT_MODULE_SCREEN_NAME, $request, $account));

        EmailHandler::setModule(JOB_BOARD_MODULE_SCREEN_NAME)
            ->setVariableValues([
                'account_type' => Str::lower($account->type->label()),
                'account_name' => $account->name,
                'account_email' => $account->email,
            ])
            ->sendUsingTemplate('account-registered', setting('email_from_address'));

        if (setting('verify_account_email', 0)) {
            $account->sendEmailVerificationNotification();

            $this->registered($request, $account);

            $message = __('We have sent you an email to verify your email. Please check and confirm your email address!');

            return $this
                ->httpResponse()
                ->setNextUrl(route('public.account.login'))
                ->with(['auth_warning_message' => $message])
                ->setMessage($message);
        }

        $account->confirmed_at = Carbon::now();

        $account->is_public_profile = false;

        $account->save();

        $this->guard()->login($account);

        $this->registered($request, $account);

        if ($account->isEmployer()) {
            $this->redirectTo = route('public.account.dashboard');
        }

        return $this
            ->httpResponse()->setNextUrl($this->redirectPath());
    }

    protected function create(array $data)
    {
        return Account::query()->forceCreate([
            'type' => $data['type'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => Arr::get($data, 'phone'),
            'password' => Hash::make($data['password']),
        ]);
    }
}
