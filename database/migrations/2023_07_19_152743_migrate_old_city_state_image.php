<?php

use Botble\Base\Facades\MetaBox;
use Botble\Location\Models\City;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration
{
    public function up(): void
    {
        if (! is_plugin_active('location')) {
            return;
        }

        foreach (City::query()->whereHas('metadata')->with('metadata')->get() as $city) {
            if ($image = $city->getMetaData('city_image', true)) {
                $city->image = $image;
                $city->save();

                MetaBox::deleteMetaData($city, 'city_image');
            }
        }
    }
};
