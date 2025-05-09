<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dish extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'dish';

    protected $fillable = [
        'name',
        'image_path',
        'price',
        'category_id',
    ];

    // Отношения
    public function orderPositions()
    {
        return $this->belongsToMany(OrderPosition::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Scope для популярных блюд (пример)
    public function scopePopular($query)
    {
        return $query->where('is_popular', true);
    }

    // Аксессор для полного URL изображения
    public function getImageUrlAttribute()
    {
        return $this->image_path ? asset($this->image_path) : null;
    }
}