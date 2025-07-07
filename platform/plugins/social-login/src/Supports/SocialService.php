<?php

namespace Botble\SocialLogin\Supports;

use Botble\Base\Facades\BaseHelper;
use Botble\SocialLogin\Models\SocialLogin;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SocialService
{
    public function registerModule(array $model): self
    {
        config([
            'plugins.social-login.general.supported' => array_merge(
                $this->supportedModules(),
                [$model['guard'] => $model]
            ),
        ]);

        return $this;
    }

    public function supportedModules(): array
    {
        return (array) config('plugins.social-login.general.supported', []);
    }

    public function isSupportedModule(string $model): bool
    {
        return ! ! collect($this->supportedModules())->firstWhere('model', $model);
    }

    public function isSupportedModuleByKey(string $key): bool
    {
        return ! ! $this->getModule($key);
    }

    public function getModule(string $key): ?array
    {
        return Arr::get($this->supportedModules(), $key);
    }

    public function isSupportedGuard(string $guard): bool
    {
        return in_array($guard, array_keys($this->supportedModules()));
    }

    public function getEnvDisableData(): array
    {
        return ['demo'];
    }

    public function getDataDisable(string $key): string
    {
        $setting = $this->setting($key);

        if (! $setting || mb_strlen($setting) <= 6) {
            return '******';
        }

        return Str::mask($setting, '*', 3, -3);
    }

    public function setting(string $key, bool $default = false): string
    {
        return (string) setting('social_login_' . $key, $default);
    }

    public function hasAnyProviderEnable(): bool
    {
        foreach ($this->getProviderKeys() as $value) {
            if ($this->getProviderEnabled($value)) {
                return true;
            }
        }

        return false;
    }

    public function getProviderKeys(): array
    {
        return array_keys($this->getProviders());
    }

    public function getProviders(): array
    {
        return apply_filters('social_login_providers', [
            'facebook' => $this->getDataProviderDefault(),
            'google' => $this->getDataProviderDefault(),
            'github' => $this->getDataProviderDefault(),
            'linkedin' => $this->getDataProviderDefault(),
            'linkedin-openid' => $this->getDataProviderDefault(),
        ]);
    }

    public function getDataProviderDefault(): array
    {
        return [
            'data' => [
                'app_id',
                'app_secret',
            ],
            'disable' => [
                'app_secret',
            ],
        ];
    }

    public function getProviderEnabled(string $provider): bool
    {
        return (bool) $this->setting($provider . '_enable');
    }

    public function getProviderKeysEnabled(): array
    {
        return collect($this->getProviderKeys())
            ->filter(function ($key) {
                return $this->getProviderEnabled($key);
            })
            ->toArray();
    }

    public function refreshToken(string $provider, string $refreshToken): ?array
    {
        if (! $this->getProviderEnabled($provider)) {
            return null;
        }

        try {
            $response = Http::post($this->getTokenRefreshEndpoint($provider), [
                'client_id' => $this->setting($provider . '_app_id'),
                'client_secret' => $this->setting($provider . '_app_secret'),
                'refresh_token' => $refreshToken,
                'grant_type' => 'refresh_token',
            ]);

            return json_decode($response->getBody(), true);
        } catch (Exception $e) {
            BaseHelper::logError($e);

            return null;
        }
    }

    protected function getTokenRefreshEndpoint(string $provider): string
    {
        return match ($provider) {
            'google' => 'https://oauth2.googleapis.com/token',
            'facebook' => 'https://graph.facebook.com/v18.0/oauth/access_token',
            'github' => 'https://github.com/login/oauth/access_token',
            'linkedin' => 'https://www.linkedin.com/oauth/v2/accessToken',
            default => throw new \InvalidArgumentException("Unsupported provider: {$provider}"),
        };
    }

    public function canLinkAccount(string $provider, string $email, string $modelClass): bool
    {
        // Check if there's a user with this email
        $user = $modelClass::query()->where('email', $email)->first();

        if (! $user) {
            return false;
        }

        // Check if the user already has a social login for this provider
        $existingSocialLogin = SocialLogin::query()
            ->where('user_id', $user->getKey())
            ->where('user_type', $modelClass)
            ->where('provider', $provider)
            ->exists();

        return ! $existingSocialLogin;
    }

    public function linkAccount(string $provider, string $email, string $modelClass, array $socialData): bool
    {
        $user = $modelClass::query()->where('email', $email)->first();

        if (! $user) {
            return false;
        }

        // Check if social login already exists for this provider
        $existingSocialLogin = SocialLogin::query()
            ->where('provider', $provider)
            ->where('provider_id', $socialData['id'])
            ->first();

        if ($existingSocialLogin) {
            // Update existing social login to link to this user
            return $existingSocialLogin->update([
                'user_id' => $user->getKey(),
                'user_type' => $modelClass,
                'token' => $socialData['token'],
                'refresh_token' => $socialData['refresh_token'] ?? null,
                'token_expires_at' => $socialData['expires_in'] ? Carbon::now()->addSeconds(
                    $socialData['expires_in']
                ) : null,
                'provider_data' => [
                    'name' => $socialData['name'],
                    'email' => $socialData['email'],
                    'avatar' => $socialData['avatar'],
                ],
            ]);
        }

        // Create new social login
        return SocialLogin::query()
                ->create([
                    'user_id' => $user->getKey(),
                    'user_type' => $modelClass,
                    'provider' => $provider,
                    'provider_id' => $socialData['id'],
                    'token' => $socialData['token'],
                    'refresh_token' => $socialData['refresh_token'] ?? null,
                    'token_expires_at' => $socialData['expires_in'] ? Carbon::now()->addSeconds(
                        $socialData['expires_in']
                    ) : null,
                    'provider_data' => [
                        'name' => $socialData['name'],
                        'email' => $socialData['email'],
                        'avatar' => $socialData['avatar'],
                    ],
                ]) !== null;
    }
}
