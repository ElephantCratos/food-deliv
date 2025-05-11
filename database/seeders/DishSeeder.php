<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Dish;

class DishSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dish = Dish::create([
            'id' => '1',
            'name' =>'Pizza Margaritta',
            'image_path' => 'Images/1745327263.jpg',
            'price' => '200',
            'category_id' => rand(1,3),
        ]);

        $dish = Dish::create([
            'id' => '2',
            'name' =>'Pizza Pepperoni',
            'image_path' => 'Images/1745327263.jpg',
            'price' => '250',
            'category_id' => rand(1,3),
        ]);
    }
}
