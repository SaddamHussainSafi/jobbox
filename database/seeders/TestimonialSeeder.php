<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Testimonial\Models\Testimonial;

class TestimonialSeeder extends BaseSeeder
{
    public function run(): void
    {
        $this->uploadFiles('testimonials');

        $testimonials = [
            [
                'name' => 'Ellis Kim',
                'company' => 'Digital Artist',
            ],
            [
                'name' => 'John Smith',
                'company' => 'Product designer',
            ],
            [
                'name' => 'Sayen Ahmod',
                'company' => 'Developer',
            ],
            [
                'name' => 'Tayla Swef',
                'company' => 'Graphic designer',
            ],
        ];

        Testimonial::query()->truncate();

        $faker = $this->fake();

        foreach ($testimonials as $index => $item) {
            Testimonial::query()->create([
                'name' => $item['name'],
                'company' => $item['company'],
                'image' => 'testimonials/' . ($index + 1) . '.png',
                'content' => $faker->realText(),
            ]);
        }
    }
}
