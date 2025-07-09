<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::statement('TRUNCATE users RESTART IDENTITY CASCADE');
        $faker = Faker::create();
        $users = [
            [
                'name' => 'Test User',
                'email' => $faker->unique()->safeEmail(),
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Admin User',
                'email' => $faker->unique()->safeEmail(),
                'password' => Hash::make('admin123'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
            ];
        DB::table('users')->insert($users);

        $products = [
            [
                'name' => $faker->word(),
                'description' => $faker->sentence(),
                'category' => $faker->word(),
                'price' => $faker->randomFloat(2, 5, 100),
                'stock' => $faker->numberBetween(1, 100),
                'image' => $faker->imageUrl(640, 480, 'products'),
                'discount' => $faker->numberBetween(0, 20), // Assuming a discount field
                'rating' => $faker->numberBetween(1, 5), // Assuming a rating field
                'status' => 'available', // Assuming a status field
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => $faker->word(),
                'description' => $faker->sentence(),
                'category' => $faker->word(),
                'price' => $faker->randomFloat(2, 5, 100),
                'stock' => $faker->numberBetween(1, 100),
                'image' => $faker->imageUrl(640, 480, 'products'),
                'discount' => $faker->numberBetween(0, 20), // Assuming a discount field
                'rating' => $faker->numberBetween(1, 5), // Assuming a rating field
                'status' => 'available', // Assuming a status field
                'created_at' => now(),
                'updated_at' => now(),
            ]
            ];
        DB::table('products')->insert($products);
    }
}
