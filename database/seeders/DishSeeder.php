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
            'description' => 'ебать ебать ебать ебать ебать ебатьебать  ебать ебать ебать ебать ебать ебатьебатьебать ебать ебать ебатьебать ебатьебатьебать ебатьебатьебать ебатьебать'
        ]);

        $dish = Dish::create([
            'id' => '2',
            'name' =>'Pizza Pepperoni',
            'image_path' => 'Images/1745327263.jpg',
            'price' => '250',
            'category_id' => rand(1,3),
            'description' => 'хуй хуй хуй хуй хуйхуй хуйхуйхуй хуйхуйхуйхуйхуй хуй хуй хуй хуй хуй хуй хуйхуй хуй хуй хуйхуйхуйхуй хуй хуй хуй'
        ]);
    }
}
