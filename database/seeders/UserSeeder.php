<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Eu 🤓☝🏻
        User::create([
            'name' => 'Christian André Steffens',
            'email' => 'oficialsteffens@hotmail.com',
            'phone' => '5551999304836',
            'password' => Hash::make('123123123'),
            'profile_photo_path' => '/assets/images/perfil.jpg',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Mc Lovin 😻
        User::create([
            'name' => 'Mc Lovin',
            'email' => 'mclovin@hotmail.com',
            'phone' => '5551999999999',
            'password' => Hash::make('123123123'),
            'profile_photo_path' => '/assets/images/mclovin.jpg',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Usuários aleatórios 🤠
        for ($i = 1; $i <= 50; $i++) {
            User::create([
                'name' => $faker->name,
                'email' => $faker->email,
                'phone' => $faker->numerify('55###########'),
                'password' => Hash::make('123123123'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
