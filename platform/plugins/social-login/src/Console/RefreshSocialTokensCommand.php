<?php

namespace Botble\SocialLogin\Console;

use Botble\SocialLogin\Facades\SocialService;
use Botble\SocialLogin\Models\SocialLogin;
use Botble\SocialLogin\Services\SocialLoginService;
use Carbon\Carbon;
use Illuminate\Console\Command;

use function Laravel\Prompts\{error, info, progress};

use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand('cms:social-login:refresh-tokens', 'Refresh expired social login tokens')]
class RefreshSocialTokensCommand extends Command
{
    protected int $refreshed = 0;

    protected int $failed = 0;

    public function __construct(
        protected SocialLoginService $socialLoginService
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        info('Starting social token refresh...');

        $socialLogins = SocialLogin::query()
            ->whereNotNull('refresh_token')
            ->whereNotNull('token_expires_at')
            ->where('token_expires_at', '<', Carbon::now()->addDay())
            ->get();

        if ($socialLogins->isEmpty()) {
            info('No tokens need to be refreshed!');

            return self::SUCCESS;
        }

        $progress = progress(
            label: 'Refreshing social login tokens',
            steps: $socialLogins->count(),
        );

        $progress->start();

        foreach ($socialLogins as $socialLogin) {
            $newTokens = SocialService::refreshToken($socialLogin->provider, $socialLogin->refresh_token);

            if (! $newTokens) {
                error("Failed to refresh token for social login {$socialLogin->id}");
                $this->failed++;

                $progress->advance();

                continue;
            }

            $this->socialLoginService->updateSocialLogin($socialLogin->user, $socialLogin->provider, [
                'token' => $newTokens['access_token'],
                'refresh_token' => $newTokens['refresh_token'] ?? $socialLogin->refresh_token,
                'token_expires_at' => Carbon::now()->addSeconds($newTokens['expires_in'] ?? 3600),
            ]);

            $this->refreshed++;
            $progress->advance();
        }

        $progress->finish();

        $this->showResults();

        return self::SUCCESS;
    }

    protected function showResults(): void
    {
        if ($this->refreshed) {
            info("✔ {$this->refreshed} tokens refreshed successfully");
        }

        if ($this->failed) {
            error("✖ {$this->failed} tokens failed to refresh");
        }

        if (! $this->refreshed && ! $this->failed) {
            info('No tokens were processed');
        }
    }
}
