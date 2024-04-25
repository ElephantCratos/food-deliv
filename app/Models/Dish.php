<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    use HasFactory;

    protected $table = 'dish';

    protected $fillable = [
        'name',
        'image_path',
        'price',
    ];

    public function order_position()
    {
        return $this->belongsToMany(OrderPosition::class);
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'dish_and_ingredients', 'dish_id', 'ingredients_id');
    }

}
