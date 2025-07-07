<?php

namespace Database\Seeders;

use Botble\Base\Facades\MetaBox;
use Botble\Base\Supports\BaseSeeder;
use Botble\JobBoard\Models\Company;
use Botble\Location\Models\City;
use Botble\Slug\Facades\SlugHelper;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends BaseSeeder
{
    public function run(): void
    {
        Company::query()->truncate();
        DB::table('jb_companies_accounts')->truncate();

        $this->uploadFiles('companies');

        $faker = $this->fake();

        $data = [
            [
                'name' => 'LinkedIn',
                'logo' => 'companies/1.png',
                'website' => 'https://www.linkedin.com/',
                'ceo' => 'John Doe',
                'latitude' => '-37.81411',
                'longitude' => '144.96328',
            ],
            [
                'name' => 'Adobe Illustrator',
                'logo' => 'companies/2.png',
                'website' => 'https://www.adobe.com/',
                'ceo' => 'Jeff Werner',
                'latitude' => '40.7128',
                'longitude' => '-74.0060',
            ],
            [
                'name' => 'Bing Search',
                'logo' => 'companies/3.png',
                'website' => 'https://www.bing.com/',
                'ceo' => 'Nakamura',
                'latitude' => '35.658517',
                'longitude' => '139.744233',
            ],
            [
                'name' => 'Dailymotion',
                'logo' => 'companies/4.png',
                'website' => 'https://www.dailymotion.com/',
                'ceo' => 'John Doe',
                'latitude' => '37.774929',
                'longitude' => '-122.419416',
            ],
            [
                'name' => 'Linkedin',
                'logo' => 'companies/5.png',
                'website' => 'https://www.linkedin.com/',
                'ceo' => 'John Doe',
                'latitude' => '10.848',
                'longitude' => '106.638',
            ],
            [
                'name' => 'Quora JSC',
                'logo' => 'companies/6.png',
                'website' => 'https://www.quora.com/',
                'ceo' => 'John Doe',
            ],
            [
                'name' => 'Nintendo',
                'logo' => 'companies/7.png',
                'website' => 'https://www.nintendo.com/',
                'ceo' => 'Steve Jobs',
            ],
            [
                'name' => 'Periscope',
                'logo' => 'companies/8.png',
                'website' => 'https://www.pscp.tv/',
                'ceo' => 'John Doe',
            ],
            [
                'name' => 'NewSum',
                'logo' => 'companies/4.png',
                'website' => 'https://newsum.us/',
                'ceo' => 'John Doe',
            ],
            [
                'name' => 'PowerHome',
                'logo' => 'companies/5.png',
                'website' => 'https://www.pscp.tv/',
                'ceo' => 'John Doe',
            ],
            [
                'name' => 'Whop.com',
                'logo' => 'companies/6.png',
                'website' => 'https://whop.com/',
                'ceo' => 'John Doe',
            ],
            [
                'name' => 'Greenwood',
                'logo' => 'companies/7.png',
                'website' => 'https://www.greenwoodjs.io/',
                'ceo' => 'John Doe',
            ],
            [
                'name' => 'Kentucky',
                'logo' => 'companies/8.png',
                'website' => 'https://www.kentucky.gov/',
                'ceo' => 'John Doe',
            ],
            [
                'name' => 'Equity',
                'logo' => 'companies/6.png',
                'website' => 'https://www.equity.org.uk/',
                'ceo' => 'John Doe',
            ],
            [
                'name' => 'Honda',
                'logo' => 'companies/9.png',
                'website' => 'https://www.honda.com/',
                'ceo' => 'John Doe',
            ],
            [
                'name' => 'Toyota',
                'logo' => 'companies/5.png',
                'website' => 'https://www.toyota.com/',
                'ceo' => 'John Doe',
            ],
            [
                'name' => 'Lexus',
                'logo' => 'companies/3.png',
                'website' => 'https://www.pscp.tv/',
                'ceo' => 'John Doe',
            ],
            [
                'name' => 'Ondo',
                'logo' => 'companies/6.png',
                'website' => 'https://ondo.mn/',
                'ceo' => 'John Doe',
            ],
            [
                'name' => 'Square',
                'logo' => 'companies/2.png',
                'website' => 'https://squareup.com/',
                'ceo' => 'John Doe',
            ],
            [
                'name' => 'Visa',
                'logo' => 'companies/8.png',
                'website' => 'https://visa.com/',
                'ceo' => 'John Doe',
            ],
        ];

        $cities = City::query()->get();

        $content = '<p class="text-muted"> Objectively pursue diverse catalysts for change for interoperable meta-services. Distinctively re-engineer
                revolutionary meta-services and premium architectures. Intrinsically incubate intuitive opportunities and
                real-time potentialities. Appropriately communicate one-to-one technology.</p>

            <p class="text-muted">Intrinsically incubate intuitive opportunities and real-time potentialities Appropriately communicate
                one-to-one technology.</p>

            <p class="text-muted"> Exercitation photo booth stumptown tote bag Banksy, elit small batch freegan sed. Craft beer elit
                seitan exercitation, photo booth et 8-bit kale chips proident chillwave deep v laborum. Aliquip veniam delectus, Marfa
                eiusmod Pinterest in do umami readymade swag.</p>';

        $faker = $this->fake();

        $employerIds = [1, 4];
        foreach ($data as $index => $item) {
            $item['latitude'] = $faker->latitude(42.4772, 44.0153);
            $item['longitude'] = $faker->longitude(-74.7624, -76.7517);

            $city = $cities->random();
            $data = [
                'content' => $content,
                'logo' => $item['logo'],
                'is_featured' => $index < 15,
                'phone' => $faker->e164PhoneNumber(),
                'year_founded' => $faker->year(),
                'number_of_offices' => $faker->numberBetween(1, 10),
                'number_of_employees' => $faker->numberBetween(1, 10),
                'annual_revenue' => $faker->numberBetween(1, 10) . 'M',
                'description' => $faker->text(),
                'latitude' => Arr::get($item, 'latitude', $faker->latitude()),
                'longitude' => Arr::get($item, 'longitude', $faker->longitude()),
                'city_id' => $city->id,
                'state_id' => $city->state_id,
                'country_id' => $city->country_id,
                'address' => $faker->address(),
            ];

            $item = array_merge($item, $data);

            $company = Company::query()->create($item);
            $company->accounts()->attach($employerIds);

            MetaBox::saveMetaBoxData($company, 'cover_image', 'companies/company-cover-image.png');

            SlugHelper::createSlug($company);
        }
    }
}
