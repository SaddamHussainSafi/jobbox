<?php

use Botble\Media\Facades\RvMedia;
use Botble\Setting\Models\Setting;
use Botble\Theme\Facades\Theme;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\File;
use Mimey\MimeTypes;

return new class() extends Migration {
    public function up(): void
    {
        try {
            $mimeType = new MimeTypes();

            $folderPath = database_path('migrations/socials');
            foreach (File::allFiles($folderPath) as $file) {
                $type = $mimeType->getMimeType(File::extension($file));
                RvMedia::uploadFromPath($file, 0, 'socials', $type);
            }

            $socialLinks = json_decode(theme_option('social_links'), true);

            foreach($socialLinks as $key => $social) {
                if (! isset($social[1])) {
                    continue;
                }

                switch ($social[1]['value']) {
                    case 'uil uil-facebook-messenger-alt':
                    case 'uil uil-facebook':
                        $socialLinks[$key][1]['value'] = 'socials/facebook.png';
                        break;
                    case 'uil uil-instagram':
                    case 'uil uil-envelope':
                    case 'uil uil-whatsapp':
                        unset($socialLinks[$key]);
                        break;
                    case 'uil uil-twitter-alt':
                        $socialLinks[$key][1]['value'] = 'socials/twitter.png';
                        break;
                    case 'uil uil-linkedin':
                        $socialLinks[$key][1]['value'] = 'socials/linkedin.png';
                        break;
                }
            }

            $theme = Theme::getThemeName();

            Setting::query()->where('key', 'theme-' . $theme . '-social_links')->delete();

            Setting::query()->insertOrIgnore([
                'key' => 'theme-' . $theme . '-social_links',
                'value' => json_encode($socialLinks),
            ]);
        } catch (Throwable) {}
    }
};
