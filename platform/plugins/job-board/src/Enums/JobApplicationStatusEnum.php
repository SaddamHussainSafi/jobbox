<?php

namespace Botble\JobBoard\Enums;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Supports\Enum;
use Illuminate\Support\HtmlString;

/**
 * @method static JobApplicationStatusEnum PENDING()
 * @method static JobApplicationStatusEnum CHECKED()
 */
class JobApplicationStatusEnum extends Enum
{
    public const PENDING = 'pending';
    public const CHECKED = 'checked';

    public static $langPath = 'plugins/job-board::job-application.statuses';

    public function toHtml(): HtmlString|string
    {
        $color = match ($this->value) {
            self::PENDING => 'warning',
            self::CHECKED => 'success',
            default => 'primary',
        };

        return BaseHelper::renderBadge($this->label(), $color);
    }
}
