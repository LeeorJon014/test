<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Role;

class NewUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $name = 'Nick Descallar';
        $email = 'nickdescallar@gmail.com'; 
        
        // Generate a random password
        $rawPassword = $this->generateRandomPassword();
        
        // Encrypt the raw password
        $password = Hash::make($rawPassword);
        
        // Create the user
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'company_id' => 1,
        ]);

        // Check if a role ID is provided
        $roleId = 4; 
        if ($roleId !== null) {
            $role = Role::find($roleId);
            if ($role) {
                $user->roles()->sync([$role->id]);
            }
        }
        
        // Send email verification notification with the raw password
        $user->sendEmailVerificationNotificationFunction($rawPassword);
    }
    
    /**
     * Generate a random password.
     *
     * @return string
     */
    protected function generateRandomPassword()
    {
        // Generate a random string of length 6 containing letters and numbers
        $randomString = Str::random(6);
        
        // Define an array of special characters
        $specialCharacters = ['!', '@', '#', '$', '%', '^', '&', '*', '_', '-'];
        
        // Choose a random special character from the array
        $randomSpecialChar = $specialCharacters[array_rand($specialCharacters)];
        
        // Generate a random 4-digit number
        $randomNumber = mt_rand(1000, 9999);
        
        // Concatenate the random string, special character, and number to create the raw password
        return $randomString . $randomSpecialChar . $randomNumber;
    }
}


