<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promocode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'discount', 'type', 'valid_from', 'valid_to', 
        'usage_limit', 'used_count', 'is_active'
    ];
    
    protected $dates = ['valid_from', 'valid_to'];
    
    public function isValid()
    {
        return $this->is_active 
            && now()->between($this->valid_from, $this->valid_to)
            && ($this->usage_limit === null || $this->used_count < $this->usage_limit);
    }
}
