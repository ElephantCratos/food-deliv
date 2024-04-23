<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image_path',
        'extra_ingredients_id',
        'price',
    ];

    public function order_position()
    {
        return $this->belongsToMany(OrderPosition::class);
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class);
    }

}
