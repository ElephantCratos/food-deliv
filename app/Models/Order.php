<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
            'id',
            'products_id',
            'customer_id',
            'courier_id',
            'price',
            'status_id',
            'address',
            'comment',
            'expected_at'
        ];

    public function positions()
    {
        return $this->belongsToMany(OrderPosition::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function promocode()
    {
        return $this->belongsTo(Promocode::class);
    }
}
