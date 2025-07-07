<?php

namespace Botble\SocialLogin\Http\Controllers;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Media\Facades\RvMedia;
use Botble\SocialLogin\Facades\SocialService;
use Botble\SocialLogin\Services\SocialLoginService;
use Carbon\Carbon;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\AbstractUser;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class SocialLoginController extends BaseController
{
    public function __construct(protected SocialLoginService $socialLoginService)
    {
    }

    public function redirectToProvider(string $provider, Request $request)
    {
        $this->ensureProviderIsExisted($provider);

        $guard = $this->guard($request);

        if (! $guard) {
            return $this
                ->httpResponse()
                ->setError()
                ->setNextUrl(BaseHelper::getHomepageUrl());
        }

        if (BaseHelper::hasDemoModeEnabled() && $provider !== 'google') {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('This feature is temporary disabled in demo mode. Please use another login option. Such as Google.'));
        }

        $this->setProvider($provider);

        session(['social_login_guard_current' => $guard]);

        return Socialite::driver($provider)->redirect();
    }

    protected function guard(Request $request = null)
    {
        if ($request) {
            $guard = $request->input('guard');
        } else {
            $guard = session('social_login_guard_current');
        }

        if (! $guard) {
            $guard = array_key_first(SocialService::supportedModules());
        }

        if (! $guard || ! SocialService::isSupportedModuleByKey($guard) || Auth::guard($guard)->check()) {
            return false;
        }

        return $guard;
    }

    protected function setProvider(string $provider): bool
    {
        config()->set([
            'services.' . $provider => [
                'client_id' => SocialService::setting($provider . '_app_id'),
                'client_secret' => SocialService::setting($provider . '_app_secret'),
                'redirect' => route('auth.social.callback', $provider),
            ],
        ]);

        return true;
    }

    public function handleProviderCallback(string $provider)
    {
        $this->ensureProviderIsExisted($provider);

        $guard = $this->guard();

        if (! $guard) {
            return $this
                ->httpResponse()
                ->setError()
                ->setNextUrl(BaseHelper::getHomepageUrl())
                ->setMessage(__('An error occurred while trying to login'));
        }

        $this->setProvider($provider);

        $providerData = Arr::get(SocialService::supportedModules(), $guard);

        try {
            /**
             * @var AbstractUser $oAuth
             */
            $oAuth = Socialite::driver($provider)->user();
        } catch (Exception $exception) {
            $message = $exception->getMessage();

            if (in_array($provider, ['github', 'facebook'])) {
                $message = json_encode($message);
            }

            if (! $message) {
                $message = __('An error occurred while trying to login');
            }

            if ($exception instanceof InvalidStateException) {
                $message = __('InvalidStateException occurred while trying to login');
            }

            return $this
                ->httpResponse()
                ->setError()
                ->setNextUrl($providerData['login_url'])
                ->setMessage($message);
        }

        if (! $oAuth->getEmail()) {
            return $this
                ->httpResponse()
                ->setError()
                ->setNextUrl($providerData['login_url'])
                ->setMessage(__('Cannot login, no email provided!'));
        }

        $avatarId = null;
        $avatarUrl = null;

        $model = new $providerData['model']();

        // Find account by email
        $account = $this->socialLoginService->findUserByEmail($oAuth->getEmail(), $model::class);

        // Find social login by provider and provider ID
        $socialLoginUser = $this->socialLoginService->findUserByProvider($provider, $oAuth->getId());

        if ($socialLoginUser && $account && $socialLoginUser->getKey() !== $account->getKey()) {
            // If social login exists but belongs to a different account, update it
            $this->socialLoginService->updateSocialLogin($socialLoginUser, $provider, [
                'user_id' => $account->getKey(),
                'user_type' => $account::class,
            ]);
        } elseif (! $account) {
            // Create new account if not exists
            $beforeProcessData = apply_filters('social_login_before_creating_account', null, $oAuth, $providerData);

            if ($beforeProcessData instanceof BaseHttpResponse) {
                return $beforeProcessData;
            }

            try {
                $url = $oAuth->getAvatar();
                if ($url) {
                    $result = RvMedia::uploadFromUrl($url, 0, $model->upload_folder ?: 'accounts', 'image/png');
                    if (! $result['error']) {
                        $avatarId = $result['data']->id;
                        $avatarUrl = $result['data']->url;
                    }
                }
            } catch (Exception $exception) {
                BaseHelper::logError($exception);
            }

            $data = [
                'name' => $oAuth->getName() ?: $oAuth->getEmail(),
                'email' => $oAuth->getEmail(),
                'password' => Hash::make(Str::random(36)),
            ];

            $data = apply_filters('social_login_before_saving_account', $data, $oAuth, $providerData);

            $account = $model;

            $account->fill($data);

            if ($account->isFillable('avatar_id')) {
                $account->avatar_id = $avatarId;
            } elseif ($account->isFillable('avatar')) {
                $account->avatar = $avatarUrl;
            }

            $account->confirmed_at = Carbon::now();

            $account->save();

            event(new Registered($account));
        }

        // Create or update social login
        $socialLoginData = $this->socialLoginService->createSocialLoginData([
            'provider' => $provider,
            'id' => $oAuth->getId(),
            'token' => $oAuth->token,
            'refresh_token' => $oAuth->refreshToken,
            'expires_in' => $oAuth->expiresIn,
            'name' => $oAuth->getName(),
            'email' => $oAuth->getEmail(),
            'avatar' => $oAuth->getAvatar(),
        ]);

        if ($this->socialLoginService->hasSocialLogin($account, $provider)) {
            $this->socialLoginService->updateSocialLogin($account, $provider, $socialLoginData);
        } else {
            $this->socialLoginService->addSocialLogin($account, $socialLoginData);
        }

        // Update user information if changed
        if ($account->name !== $oAuth->getName() || $account->email !== $oAuth->getEmail()) {
            $account->name = $oAuth->getName() ?: $account->name;
            $account->email = $oAuth->getEmail();
            $account->save();
        }

        // Update avatar if changed
        try {
            $url = $oAuth->getAvatar();
            if ($url && (! $account->avatar_id || $account->avatar_id !== $avatarId)) {
                $result = RvMedia::uploadFromUrl($url, 0, $model->upload_folder ?: 'accounts', 'image/png');

                if (! $result['error']) {
                    if ($account->isFillable('avatar_id')) {
                        $account->avatar_id = $result['data']->id;
                    } elseif ($account->isFillable('avatar')) {
                        $account->avatar = $result['data']->url;
                    }

                    $account->save();
                }
            }
        } catch (Exception $exception) {
            BaseHelper::logError($exception);
        }

        Auth::guard($guard)->login($account, true);

        $redirectUrl = $providerData['redirect_url'] ?: BaseHelper::getHomepageUrl();

        $redirectUrl = session()->has('url.intended') ? session('url.intended') : $redirectUrl;

        return $this
            ->httpResponse()
            ->setNextUrl($redirectUrl)
            ->setMessage(trans('core/acl::auth.login.success'));
    }

    protected function ensureProviderIsExisted(string $provider): void
    {
        abort_if(! in_array($provider, SocialService::getProviderKeys(), true), 404);
    }
}
