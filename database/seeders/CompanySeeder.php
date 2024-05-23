<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use App\Models\OfficeAddress;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $officeAddresses = OfficeAddress::all();

        foreach ($officeAddresses as $officeAddress) {
            DB::table('companies')->insert([
                'name' => $faker->company,
                'office_address_id' => $officeAddress->id,
            ]);
        }

        // Use when list of RIPs are available.
        // $this->seedCompanies($this->getCompanies());
    }

    /**
     * Get companies to be inserted to the database.
     *
     * @return array
     */
    private function getCompanies(): array
    {
        return [
            [
                'name' => 'Company A',
                'office_address_id' => '1',
            ],
            [
                'name' => 'Company B',
                'office_address_id' => '2',
            ],
        ];
    }

    /**
     * Populate the database with dummy data.
     *
     * @return void
     */
    private function seedCompanies(array $companies): void
    {
        foreach ($companies as $company) {
            Company::updateOrCreate(
                [
                    'name' => $company['name']
                ],
                [
                    'office_address_id' => $company['office_address_id'],
                ]
            );
        }
    }
}
