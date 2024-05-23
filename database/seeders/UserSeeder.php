<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create fake users
        $faker = Faker::create();
        $companyIds = Company::pluck('id')->toArray();
        $users = [];

        foreach (range(1, 100) as $index) {
            $user = [
                'name' => $faker->name(),
                'email' => $faker->email(),
                'password' => Hash::make($faker->password()),
                'company_id' => $faker->randomElement($companyIds),
                'is_active' => $faker->numberBetween(0, 1),
                'created_at' => now(),
                'updated_at' => now()
            ];

            $users[] = $user;
        }

        User::insert($users);
    }
}
