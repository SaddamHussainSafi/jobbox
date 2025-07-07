<?php

namespace Database\Seeders;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Supports\BaseSeeder;
use Botble\Team\Models\Team;

class TeamSeeder extends BaseSeeder
{
    public function run(): void
    {
        $this->uploadFiles('teams');

        $teams = [
            [
                'name' => 'Jack Persion',
                'title' => 'Developer Fullstack',
                'location' => 'USA',
            ],
            [
                'name' => 'Tyler Men',
                'title' => 'Business Analyst',
                'location' => 'Qatar',
            ],
            [
                'name' => 'Mohamed Salah',
                'title' => 'Developer Fullstack',
                'location' => 'India',
            ],
            [
                'name' => 'Xao Shin',
                'title' => 'Developer Fullstack',
                'location' => 'China',
            ],
            [
                'name' => 'Peter Cop',
                'title' => 'Designer',
                'location' => 'Russia',
            ],
            [
                'name' => 'Jacob Jones',
                'title' => 'Frontend Developer',
                'location' => 'New York, US',
            ],
            [
                'name' => 'Court Henry',
                'title' => 'Backend Developer',
                'location' => 'Portugal',
            ],
            [
                'name' => 'Theresa',
                'title' => 'Backend Developer',
                'location' => 'Thailand',
            ],

        ];

        Team::query()->truncate();

        foreach ($teams as $index => $item) {
            $item['photo'] = 'teams/' . ($index + 1) . '.png';
            $item['socials'] = json_encode([
                'facebook' => 'fb.com',
                'twitter' => 'twitter.com',
                'instagram' => 'instagram.com',
            ]);

            $item['status'] = BaseStatusEnum::PUBLISHED;

            Team::query()->create($item);
        }
    }
}
