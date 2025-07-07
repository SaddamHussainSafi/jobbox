<?php

namespace Botble\Language\Http\Controllers\API;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Language\Facades\Language;
use Illuminate\Http\JsonResponse;

class LanguageController extends BaseController
{
    /**
     * Get list of available languages
     *
     * @group Languages
     *
     * @response {
     *   "error": false,
     *   "data": [
     *     {
     *       "lang_id": 1,
     *       "lang_name": "English",
     *       "lang_locale": "en",
     *       "lang_code": "en_US",
     *       "lang_flag": "us",
     *       "lang_is_default": true,
     *       "lang_is_rtl": false,
     *       "lang_order": 0
     *     },
     *     {
     *       "lang_id": 2,
     *       "lang_name": "Vietnamese",
     *       "lang_locale": "vi",
     *       "lang_code": "vi",
     *       "lang_flag": "vn",
     *       "lang_is_default": false,
     *       "lang_is_rtl": false,
     *       "lang_order": 1
     *     }
     *   ],
     *   "message": null
     * }
     */
    public function index()
    {
        $languages = Language::getActiveLanguage();

        return response()
            ->json($languages);
    }

    /**
     * Get current language
     *
     * @group Languages
     *
     * @response {
     *   "error": false,
     *   "data": {
     *     "lang_id": 1,
     *     "lang_name": "English",
     *     "lang_locale": "en",
     *     "lang_code": "en_US",
     *     "lang_flag": "us",
     *     "lang_is_default": true,
     *     "lang_is_rtl": false,
     *     "lang_order": 0
     *   },
     *   "message": null
     * }
     */
    public function getCurrentLanguage(): JsonResponse
    {
        $currentLocale = Language::getCurrentLocale();
        $languages = Language::getActiveLanguage();
        $currentLanguage = $languages->where('lang_locale', $currentLocale)->first();

        return response()
            ->json($currentLanguage);
    }
}
