<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ingredient;
use App\Models\Dish;
use App\Models\DishAndIngredient;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pepperoni = Ingredient::create([
            'id' => '1',
            'name' => 'Pepperoni',
            'description' => 'zxc',
            'price' => '10',
        ]);

        $tomato = Ingredient::create([
            'id' => '2',
            'name' => 'Tomato',
            'description' => 'zxc',
            'price' => '10',
        ]);

        $cheese = Ingredient::create([
            'id' => '3',
            'name' => 'Cheese',
            'description' => 'zxc',
            'price' => '10',
        ]);

        $dish = Dish::create([
            'id' => '1',
            'name' =>'Pizza Margaritta',
            'image_path' => 'images/1695870651_gas-kvas-com-p-kartinki-pitstsa-1.jpg',
            'category_id' => rand(1,3),
            'price' => '200',
        ]);

        $dishAndIngredient = DishAndIngredient::create([
            'dish_id' => $dish->id,
            'ingredients_id' => $tomato->id,
        ]);

        $dishAndIngredient = DishAndIngredient::create([
            'dish_id' => $dish->id,
            'ingredients_id' => $cheese->id,
        ]);

        $dish = Dish::create([
            'id' => '2',
            'name' =>'Pizza Pepperoni',
            'image_path' => 'images/1703351039_mykaleidoscope-ru-p-zelenaya-pitstsa-krasivo-62.jpg',
            'category_id' => rand(1,3),
            'price' => '250',
        ]);

        $dishAndIngredient = DishAndIngredient::create([
            'dish_id' => $dish->id,
            'ingredients_id' => $tomato->id,
        ]);

        $dishAndIngredient = DishAndIngredient::create([
            'dish_id' => $dish->id,
            'ingredients_id' => $cheese->id,
        ]);

        $dishAndIngredient = DishAndIngredient::create([
            'dish_id' => $dish->id,
            'ingredients_id' => $pepperoni->id,
        ]);
    }
}
