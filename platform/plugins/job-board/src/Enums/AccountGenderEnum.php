<?php

namespace Botble\JobBoard\Enums;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Supports\Enum;
use Illuminate\Support\HtmlString;

/**
 * @method static AccountGenderEnum MALE()
 * @method static AccountGenderEnum FEMALE()
 * @method static AccountGenderEnum OTHER()
 */
class AccountGenderEnum extends Enum
{
    public const MALE   = 'male';

    public const FEMALE = 'female';

    public const OTHER  = 'other';

    public static $langPath = 'plugins/job-board::account.enums.genders';

    public function toHtml(): HtmlString|string
    {
        $color = match ($this->value) {
            self::MALE => 'info',
            self::FEMALE => 'success',
            default => 'primary',
        };

        return BaseHelper::renderBadge($this->label(), $color);
    }
}
