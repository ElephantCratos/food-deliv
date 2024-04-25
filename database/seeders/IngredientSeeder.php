<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ingredient;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ingredients = Ingredient::create([
            'id' => '1',
            'name' => 'Potato',
            'description' => 'zxc',
            'price' => '10',
        ]);

        $ingredients = Ingredient::create([
            'id' => '2',
            'name' => 'Tomato',
            'description' => 'zxc',
            'price' => '10',
        ]);
    }
}
