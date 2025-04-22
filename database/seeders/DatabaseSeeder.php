<?php

namespace Database\Seeders;


// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            CategorySeeder::class,
            PermissionSeeder::class,
            AdminSeeder::class,
            ManagerSeeder::class,
            KitchenSeeder::class,
            CourierSeeder::class,
            UserSeeder::class,
            StatusSeeder::class,
            IngredientSeeder::class

        ]);
    }
}
