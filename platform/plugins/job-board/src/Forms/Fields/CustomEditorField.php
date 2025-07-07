<?php

namespace Botble\JobBoard\Forms\Fields;

use Botble\Base\Facades\Assets;
use Botble\Base\Forms\FormField;
use Botble\JobBoard\Facades\JobBoardHelper;

class CustomEditorField extends FormField
{
    protected function getTemplate(): string
    {
        Assets::addScriptsDirectly('vendor/core/core/base/libraries/tinymce/tinymce.min.js');

        return JobBoardHelper::viewPath('dashboard.forms.fields.custom-editor');
    }
}
