<?php

namespace Botble\JobBoard\Enums;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Supports\Enum;
use Illuminate\Support\HtmlString;

/**
 * @method static ModerationStatusEnum APPROVED()
 * @method static ModerationStatusEnum PENDING()
 * @method static ModerationStatusEnum REJECTED()
 */
class ModerationStatusEnum extends Enum
{
    public const APPROVED = 'approved';

    public const PENDING = 'pending';

    public const REJECTED = 'rejected';

    public static $langPath = 'plugins/job-board::job.moderation_statuses';

    public function toHtml(): HtmlString|string
    {
        $color = match ($this->value) {
            self::APPROVED => 'success',
            self::PENDING => 'warning',
            self::REJECTED => 'danger',
            default => 'primary',
        };

        return BaseHelper::renderBadge($this->label(), $color);
    }
}
