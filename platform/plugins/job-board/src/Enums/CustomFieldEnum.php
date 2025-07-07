<?php

namespace Botble\JobBoard\Enums;

use Botble\Base\Supports\Enum;

/**
 * @method static CustomFieldEnum TEXT()
 * @method static CustomFieldEnum DROPDOWN()
 */
class CustomFieldEnum extends Enum
{
    public const TEXT = 'text';

    public const DROPDOWN = 'dropdown';

    public static $langPath = 'plugins/job-board::custom-fields.enums.fields';
}
