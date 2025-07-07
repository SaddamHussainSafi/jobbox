<?php

namespace Database\Seeders;

use Botble\ACL\Models\User;
use Botble\Base\Facades\Html;
use Botble\Base\Facades\MetaBox;
use Botble\Base\Supports\BaseSeeder;
use Botble\Blog\Models\Category;
use Botble\Blog\Models\Post;
use Botble\Blog\Models\Tag;
use Botble\Media\Facades\RvMedia;
use Botble\Slug\Facades\SlugHelper;

class BlogSeeder extends BaseSeeder
{
    public function run(): void
    {
        $this->uploadFiles('news');

        Post::query()->truncate();
        Category::query()->truncate();
        Tag::query()->truncate();

        $faker = $this->fake();

        $categories = [
            [
                'name' => 'Design',
                'is_default' => true,
            ],
            [
                'name' => 'Lifestyle',
            ],
            [
                'name' => 'Travel Tips',
                'parent_id' => 2,
            ],
            [
                'name' => 'Healthy',
            ],
            [
                'name' => 'Travel Tips',
                'parent_id' => 4,
            ],
            [
                'name' => 'Hotel',
            ],
            [
                'name' => 'Nature',
                'parent_id' => 6,
            ],
        ];

        foreach ($categories as $index => $item) {
            $item['description'] = $faker->text();
            $item['is_featured'] = ! isset($item['parent_id']) && $index != 0;

            $category = Category::query()->create($item);

            SlugHelper::createSlug($category);
        }

        $tags = [
            [
                'name' => 'New',
            ],
            [
                'name' => 'Event',
            ],
        ];

        foreach ($tags as $item) {
            $item['author_id'] = 1;
            $item['author_type'] = User::class;

            $tag = Tag::query()->create($item);

            SlugHelper::createSlug($tag);
        }

        $posts = [
            [
                'name' => 'Interview Question: Why Dont You Have a Degree?',
            ],
            [
                'name' => '21 Job Interview Tips: How To Make a Great Impression',
            ],
            [
                'name' => '39 Strengths and Weaknesses To Discuss in a Job Interview',
            ],
        ];

        foreach ($posts as $index => $item) {
            $item['content'] =
                ($index % 3 == 0 ? Html::tag(
                    'p',
                    '[youtube-video]https://www.youtube.com/watch?v=SlPhMPnQ58k[/youtube-video]'
                ) : '') .
                Html::tag('p', $faker->realText(1000)) .
                Html::tag(
                    'p',
                    Html::image(
                        RvMedia::getImageUrl('news/' . $faker->numberBetween(1, 5) . '.jpg', 'medium'),
                        'image',
                        ['style' => 'width: 100%', 'class' => 'image_resized']
                    )
                        ->toHtml(),
                    ['class' => 'text-center']
                ) .
                Html::tag('p', $faker->realText(500)) .
                Html::tag(
                    'p',
                    Html::image(
                        RvMedia::getImageUrl('news/' . $faker->numberBetween(6, 10) . '.jpg', 'medium'),
                        'image',
                        ['style' => 'width: 100%', 'class' => 'image_resized']
                    )
                        ->toHtml(),
                    ['class' => 'text-center']
                ) .
                Html::tag('p', $faker->realText(1000)) .
                Html::tag(
                    'p',
                    Html::image(
                        RvMedia::getImageUrl('news/' . $faker->numberBetween(11, 14) . '.jpg', 'medium'),
                        'image',
                        ['style' => 'width: 100%', 'class' => 'image_resized']
                    )
                        ->toHtml(),
                    ['class' => 'text-center']
                ) .
                Html::tag('p', $faker->realText(1000));
            $item['author_id'] = 1;
            $item['author_type'] = User::class;
            $item['views'] = $faker->numberBetween(100, 2500);
            $item['is_featured'] = $index < 6;
            $item['image'] = 'news/img-news' . ($index + 1) . '.png';
            $item['description'] = $faker->text(250);
            $item['created_at'] = $faker->dateTimeBetween('-1 month');
            $item['updated_at'] = $item['created_at'];

            $item['content'] = str_replace(url(''), '', $item['content']);

            $post = Post::query()->create($item);

            if ($index < 3) {
                MetaBox::saveMetaBoxData($post, 'cover_image', 'news/cover-image' . ($index + 1) . '.png');
            }

            $post->categories()->sync([
                $faker->numberBetween(1, 4),
                $faker->numberBetween(5, 7),
            ]);

            $post->tags()->sync([1, 2, 3, 4, 5]);

            SlugHelper::createSlug($post);
        }
    }
}
