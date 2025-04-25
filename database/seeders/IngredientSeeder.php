<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Dish;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dish = Dish::create([
            'id' => '1',
            'name' =>'Pizza Margaritta',
            'image_path' => 'images/1695870651_gas-kvas-com-p-kartinki-pitstsa-1.jpg',
            'price' => '200',
            'category_id' => rand(1,3),
        ]);

        $dish = Dish::create([
            'id' => '2',
            'name' =>'Pizza Pepperoni',
            'image_path' => 'images/1703351039_mykaleidoscope-ru-p-zelenaya-pitstsa-krasivo-62.jpg',
            'price' => '250',
            'category_id' => rand(1,3),
        ]);
    }
}
