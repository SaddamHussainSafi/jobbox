<?php

namespace Botble\JobBoard\Enums;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Supports\Enum;
use Illuminate\Support\HtmlString;

/**
 * @method static JobStatusEnum DRAFT()
 * @method static JobStatusEnum PUBLISHED()
 * @method static JobStatusEnum PENDING()
 * @method static JobStatusEnum CLOSED()
 */
class JobStatusEnum extends Enum
{
    public const PUBLISHED = 'published';

    public const DRAFT = 'draft';

    public const PENDING = 'pending';

    public const CLOSED = 'closed';

    public static $langPath = 'plugins/job-board::job.statuses';

    public function toHtml(): string|HtmlString
    {
        $color = match ($this->value) {
            self::DRAFT => 'info',
            self::CLOSED, self::PENDING => 'warning',
            self::PUBLISHED => 'success',
            default => 'primary',
        };

        return BaseHelper::renderBadge($this->label(), $color);
    }
}
