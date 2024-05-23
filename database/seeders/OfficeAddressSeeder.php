<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class OfficeAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        config(['numberOfRecords' => 5]);

        for ($i = 1; $i <= config('numberOfRecords'); $i++) {
            DB::table('office_addresses')->insert([
                'street_address' => $faker->streetAddress,
                'barangay' => $faker->word,
                'city' => $faker->city,
                'municipality' => $faker->city,
                'province' => $faker->state,
                'country' => $faker->country,
            ]);
        }
    }
}
