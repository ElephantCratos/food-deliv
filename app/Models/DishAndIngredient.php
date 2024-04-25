<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DishAndIngredient extends Model
{
    use HasFactory;

    protected $table = 'dish_and_ingredients'; // Specify the name of the pivot table

    protected $fillable = [
        'dish_id',
        'ingredient_id',
    ];
}
