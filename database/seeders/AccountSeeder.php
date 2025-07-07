<?php

namespace Database\Seeders;

use Botble\Base\Facades\MetaBox;
use Botble\Base\Supports\BaseSeeder;
use Botble\JobBoard\Enums\AccountTypeEnum;
use Botble\JobBoard\Models\Account;
use Botble\JobBoard\Models\AccountEducation;
use Botble\JobBoard\Models\AccountExperience;
use Botble\Media\Models\MediaFile;
use Botble\Slug\Facades\SlugHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class AccountSeeder extends BaseSeeder
{
    public function run(): void
    {
        $this->uploadFiles('resume');
        $files = $this->uploadFiles('avatars');
        $this->uploadFiles('covers');

        $faker = $this->fake();

        Account::query()->truncate();
        AccountExperience::query()->truncate();
        AccountEducation::query()->truncate();

        $companies = [
            'Spa Paragon',
            'GameDay Catering',
            'Exploration Kids',
            'Darwin Travel',
            'Party Plex',
        ];

        $specializeds = [
            'Anthropology',
            'Art History',
            'Classical Studies',
            'Economics',
            'Culture and Technology Studies',
        ];

        $jobs = [
            'Marketing Coordinator',
            'Web Designer',
            'Dog Trainer',
            'President of Sales',
            'Project Manager',
        ];

        $universities = [
            'Adams State College',
            'The University of the State of Alabama',
            'Associated Mennonite Biblical Seminary',
            'Antioch University McGregor',
            'American Institute of Health Technology',
            'Gateway Technical College',
        ];

        $description = 'There are many variations of passages of available, but the majority alteration in some form.
                As a highly skilled and successful product development and design specialist with more than 4 Years of
                My experience';

        $accounts = [
            [
                'email' => 'employer@archielite.com',
                'type' => AccountTypeEnum::EMPLOYER,
                'description' => 'Software Developer',
            ],
            [
                'email' => 'job_seeker@archielite.com',
                'type' => AccountTypeEnum::JOB_SEEKER,
                'description' => 'Creative Designer',
                'is_public_profile' => true,
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Harding',
                'email' => 'sarah_harding@archielite.com',
                'type' => AccountTypeEnum::EMPLOYER,
                'avatar' => 'avatars/1.png',
                'description' => 'Creative Designer',
            ],
            [
                'first_name' => 'Steven',
                'last_name' => 'Jobs',
                'email' => 'steven_jobs@archielite.com',
                'type' => AccountTypeEnum::EMPLOYER,
                'avatar' => 'avatars/2.png',
                'description' => 'Creative Designer',
            ],
            [
                'first_name' => 'William',
                'last_name' => 'Kent',
                'email' => 'william_kent@archielite.com',
                'type' => AccountTypeEnum::EMPLOYER,
                'avatar' => 'avatars/3.png',
                'description' => 'Creative Designer',
            ],
        ];

        for ($i = 0; $i < 100; $i++) {
            $accountType = isset($accounts[$i]) ? $accounts[$i]['type'] : array_rand(AccountTypeEnum::labels());

            $account = Account::query()->create([
                'first_name' => $accounts[$i]['first_name'] ?? $faker->firstName(),
                'last_name' => $accounts[$i]['last_name'] ?? $faker->lastName(),
                'email' => isset($accounts[$i]) ? $accounts[$i]['email'] : $faker->email(),
                'password' => Hash::make('12345678'),
                'dob' => $faker->dateTime(),
                'phone' => $faker->e164PhoneNumber(),
                'description' => isset($accounts[$i]) ? $accounts[$i]['description'] : $faker->realText(20),
                'avatar_id' => isset($accounts[$i]['avatar'])
                    ? MediaFile::query()->where('url', $accounts[$i]['avatar'])->value('id')
                    : $files[rand(0, 2)]['data']->id,
                'confirmed_at' => Carbon::now(),
                'type' => $accountType,
                'resume' => $accountType === AccountTypeEnum::JOB_SEEKER ? 'resume/01.pdf' : null,
                'is_public_profile' => 1,
                'bio' => $faker->realText(),
                'address' => $faker->address(),
                'is_featured' => rand(0, 1),
                'available_for_hiring' => rand(0, 1),
                'views' => rand(100, 5000),
            ]);

            if ($account->isJobSeeker()) {
                AccountEducation::query()->create([
                    'school' => $universities[rand(0, count($universities) - 1)],
                    'specialized' => $specializeds[rand(0, count($specializeds) - 1)],
                    'account_id' => $account->id,
                    'description' => $description,
                    'started_at' => Carbon::now()->toDateString(),
                    'ended_at' => Carbon::now()->toDateString(),
                ]);

                AccountExperience::query()->create([
                    'company' => $companies[rand(0, count($companies) - 1)],
                    'position' => $jobs[rand(0, count($jobs) - 1)],
                    'account_id' => $account->id,
                    'description' => $description,
                    'started_at' => Carbon::now()->toDateString(),
                    'ended_at' => Carbon::now()->toDateString(),
                ]);
            }

            MetaBox::saveMetaBoxData($account, 'cover_image', 'covers/' . (rand(1, 3)) . '.png');

            SlugHelper::createSlug($account);
        }
    }
}
