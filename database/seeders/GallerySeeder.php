<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Gallery\Models\Gallery as GalleryModel;
use Botble\Gallery\Models\GalleryMeta;
use Botble\Slug\Facades\SlugHelper;

class GallerySeeder extends BaseSeeder
{
    public function run(): void
    {
        $this->uploadFiles('galleries');

        GalleryModel::query()->truncate();
        GalleryMeta::query()->truncate();

        $faker = $this->fake();

        $galleries = [
            [
                'name' => 'Perfect',
            ],
            [
                'name' => 'New Day',
            ],
            [
                'name' => 'Happy Day',
            ],
            [
                'name' => 'Nature',
            ],
            [
                'name' => 'Morning',
            ],
            [
                'name' => 'Photography',
            ],
        ];

        $images = [];
        for ($i = 0; $i < 9; $i++) {
            $images[] = [
                'img' => 'galleries/' . ($i + 1) . '.jpg',
                'description' => $faker->text(150),
            ];
        }

        foreach ($galleries as $index => $item) {
            $item['description'] = $faker->text(150);
            $item['image'] = 'galleries/' . ($index + 1) . '.jpg';
            $item['user_id'] = 1;
            $item['is_featured'] = true;

            $gallery = GalleryModel::query()->create($item);

            SlugHelper::createSlug($gallery);

            GalleryMeta::query()->create([
                'images' => $images,
                'reference_id' => $gallery->id,
                'reference_type' => GalleryModel::class,
            ]);
        }
    }
}
