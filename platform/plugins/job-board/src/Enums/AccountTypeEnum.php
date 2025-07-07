<?php

namespace Botble\JobBoard\Enums;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Supports\Enum;
use Illuminate\Support\HtmlString;

/**
 * @method static AccountTypeEnum JOB_SEEKER()
 * @method static AccountTypeEnum EMPLOYER()
 */
class AccountTypeEnum extends Enum
{
    public const JOB_SEEKER = 'job-seeker';

    public const EMPLOYER = 'employer';

    public static $langPath = 'plugins/job-board::account.types';

    public function toHtml(): HtmlString|string
    {
        $color = match ($this->value) {
            self::JOB_SEEKER => 'info',
            self::EMPLOYER => 'success',
            default => 'primary',
        };

        return BaseHelper::renderBadge($this->label(), $color);
    }
}
