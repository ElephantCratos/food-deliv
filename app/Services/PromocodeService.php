<?php

namespace App\Services;

use App\Models\Promocode;

class PromocodeService
{
    public function validate(string $code): ?Promocode
    {
        $promocode = Promocode::where('code', $code)->first();
        
        if (!$promocode || !$promocode->isValid()) {
            return null;
        }
        
        return $promocode;
    }
    
    public function apply(Promocode $promocode, float $total): float
    {
        if ($promocode->type === 'percent') {
            return $total * (1 - $promocode->discount / 100);
        }
        
        return max(0, $total - $promocode->discount);
    }
    
    public function incrementUsage(Promocode $promocode): void
    {
        $promocode->increment('used_count');
    }
}