<?php

namespace Database\Seeders;

use Botble\Base\Facades\MetaBox;
use Botble\Base\Supports\BaseSeeder;
use Botble\JobBoard\Models\Category;
use Botble\Slug\Facades\SlugHelper;

class JobCategorySeeder extends BaseSeeder
{
    public function run(): void
    {
        $this->uploadFiles('job-categories');

        Category::query()->truncate();

        $data = [
            'Content Writer',
            'Market Research',
            'Marketing & Sale',
            'Customer Help',
            'Finance',
            'Software',
            'Human Resource',
            'Management',
            'Retail & Products',
            'Security Analyst',
        ];

        $imageData = [
            'content',
            'research',
            'marketing',
            'customer',
            'finance',
            'lightning',
            'human',
            'management',
            'retail',
            'security',
        ];

        foreach ($data as $index => $item) {
            $category = Category::query()->create([
                'name' => $item,
                'order' => $index,
                'is_featured' => $index < 8,
            ]);

            if (isset($imageData[$index])) {
                MetaBox::saveMetaBoxData($category, 'icon_image', 'general/' . $imageData[$index] . '.png');
            }

            MetaBox::saveMetaBoxData(
                $category,
                'job_category_image',
                'job-categories/img-cover-' . rand(1, 3) . '.png'
            );

            SlugHelper::createSlug($category);
        }
    }
}
