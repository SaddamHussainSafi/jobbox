<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\JobBoard\Models\Package;

class PackageSeeder extends BaseSeeder
{
    public function run(): void
    {
        Package::query()->truncate();

        $data = [
            [
                'name' => 'Basic Package',
                'price' => 0,
                'currency_id' => 1,
                'percent_save' => 0,
                'order' => 0,
                'number_of_listings' => 1,
                'account_limit' => 1,
                'is_default' => false,
                'features' => $this->parseFeatures([
                    'Basic listing',
                    'Standard support',
                    'No featured listing',
                ]),
            ],
            [
                'name' => 'Standard Package',
                'price' => 250,
                'currency_id' => 1,
                'percent_save' => 0,
                'order' => 0,
                'number_of_listings' => 1,
                'is_default' => true,
                'features' => $this->parseFeatures([
                    'Standard listing',
                    'Standard support',
                    'No featured listing',
                ]),
            ],
            [
                'name' => 'Professional Package',
                'price' => 1000,
                'currency_id' => 1,
                'percent_save' => 20,
                'order' => 0,
                'number_of_listings' => 5,
                'is_default' => false,
                'features' => $this->parseFeatures([
                    'Professional listing',
                    'Priority support',
                    'No featured listing',
                ]),
            ],
            [
                'name' => 'Premium Package',
                'price' => 5000,
                'currency_id' => 1,
                'percent_save' => 20,
                'order' => 0,
                'number_of_listings' => 50,
                'is_default' => false,
                'features' => $this->parseFeatures([
                    'Featured listing',
                    'Top of search results',
                    'Highlighted listing',
                    'Social media promotion',
                ]),
            ],
        ];

        foreach ($data as $item) {
            Package::query()->create($item);
        }
    }

    protected function parseFeatures(array $features): string
    {
        $data = [];

        foreach ($features as $feature) {
            if (is_array($feature)) {
                $data[] = $feature;
            } else {
                $data[][] = [
                    'key' => 'text',
                    'value' => $feature,
                ];
            }
        }

        return json_encode($data);
    }
}
