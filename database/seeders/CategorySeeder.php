<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category1 = Category::create([
            'code' => "kebab",
            'name' => "Люля-кебаб"
        ]);
        $category2 =  Category::create([
            'code' => "shashlik",
            'name' => "Шашлык"
        ]);
        $category3 = Category::create([
            'code' => "combo",
            'name' => "Комбо-набор"
        ]);
        $category4 = Category::create([
            'code' => "garnish",
            'name' => "Гарнир"
        ]); 
        $category5 = Category::create([
            'code' => "drink",
            'name' => "Напиток"
        ]);
        $category6 = Category::create([
            'code' => "sauce",
            'name' => "Соус"
        ]);
    }
}
