<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPosition extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'extra_ingredients_id',
        'description',
        'price',
    ];

    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }

}
