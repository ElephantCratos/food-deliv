<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
    ];

    public function order_positions()
    {
        return $this->belongsToMany(OrderPosition::class);
    }

    public function dish()
    {
        return $this->belongsToMany(Dish::class, 'dish_and_ingredients',  'dish_id', 'ingredients_id');
    }
}
