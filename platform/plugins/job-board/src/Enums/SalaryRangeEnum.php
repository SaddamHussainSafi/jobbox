<?php

namespace Botble\JobBoard\Enums;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Supports\Enum;
use Illuminate\Support\HtmlString;

/**
 * @method static SalaryRangeEnum MONTHLY()
 * @method static SalaryRangeEnum YEARLY()
 * @method static SalaryRangeEnum HOURLY()
 * @method static SalaryRangeEnum WEEKLY()
 */
class SalaryRangeEnum extends Enum
{
    public const MONTHLY = 'monthly';

    public const YEARLY = 'yearly';

    public const WEEKLY = 'weekly';

    public const DAILY = 'daily';

    public const HOURLY = 'hourly';

    public static $langPath = 'plugins/job-board::job.salary_periods';

    public function toHtml(): HtmlString|string
    {
        $color = match ($this->value) {
            self::HOURLY, self::DAILY, self::WEEKLY => 'info',
            default => 'primary',
        };

        return BaseHelper::renderBadge($this->label(), $color);
    }
}
