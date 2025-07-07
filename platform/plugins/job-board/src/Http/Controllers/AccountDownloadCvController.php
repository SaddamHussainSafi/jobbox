<?php

namespace Botble\JobBoard\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\JobBoard\Enums\AccountTypeEnum;
use Botble\JobBoard\Models\Account;
use Botble\Media\Facades\RvMedia;
use Botble\Slug\Facades\SlugHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AccountDownloadCvController extends BaseController
{
    public function __invoke(?string $slug, Request $request)
    {
        abort_unless($slug, 404);

        $slug = SlugHelper::getSlug($slug, SlugHelper::getPrefix(Account::class));

        abort_unless($slug, 404);

        $condition = [
            ['id', '=', $slug->reference_id],
            ['type', '=', AccountTypeEnum::JOB_SEEKER],
        ];

        if (setting('verify_account_email', 0)) {
            $condition[] = ['confirmed_at', '!=', null];
        }

        /**
         * @var Account $candidate
         */
        $candidate = Account::query()
            ->where($condition)
            ->firstOrFail();

        $candidate->setRelation('slugable', $slug);

        abort_if($candidate->hide_cv ||
        ! $candidate->resume ||
        $candidate->resume !== $request->input('path') ||
        ! $candidate->isJobSeeker() ||
        ! Storage::exists($candidate->resume), 404);

        return RvMedia::responseDownloadFile($candidate->resume);
    }
}
