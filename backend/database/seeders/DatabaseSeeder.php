<?php

namespace Database\Seeders;

use App\Models\Product;
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
        // Disable FK checks
        DB::statement('SET session_replication_role = replica;');

        // Truncate tables
        DB::table('product_images')->truncate();
        DB::table('products')->truncate();
        DB::table('users')->truncate();

        // Enable FK checks again
        DB::statement('SET session_replication_role = DEFAULT;');
        
        $faker = Faker::create();
        $users = [
            [
                'name' => 'John Doe',
                'email' => $faker->unique()->safeEmail(),
                'password' => Hash::make('password123'),
                'role' => 'user', // Assuming a role field
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jacke Chan',
                'email' => 'admin123@gmail.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin', // Assuming a role field
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
                'discount' => $faker->numberBetween(0, 20), // Assuming a discount field
                'rating' => $faker->numberBetween(1, 5), // Assuming a rating field
                'status' => 'available', // Assuming a status field
                'created_at' => now(),
                'updated_at' => now(),
            ],
            ];
        DB::table('products')->insert($products);
        $products = Product::all();

        $productImages = [];
        $url = 'https://placehold.co/640x480/0066cc/ffffff?text='.$faker->word();        
        foreach ($products as $product) {
            $imageCount = rand(1, 4); // Adjust the range as needed
            for ($i = 0; $i < $imageCount; $i++) {
                $productImages[] = [
                    'product_id' => $product->id,
                    'url' => $url,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        DB::table('product_images')->insert($productImages);

    }
}
