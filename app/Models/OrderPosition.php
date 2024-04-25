<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPosition extends Model
{
    use HasFactory;

    protected $table = 'order_position';
    protected $fillable = [
        'description',
        'dish_id',
        'price',
        'quantity',
    ];

    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class);
    }
    public function dish()
    {
        return $this->belongsTo(Dish::class);
    }

}
