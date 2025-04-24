<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Promocode;
use Carbon\Carbon;

class PromocodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $promocodes = [
            [
                'code' => 'GRILL25',
                'discount' => 25,
                'type' => 'percent',
                'valid_from' => Carbon::now(),
                'valid_to' => Carbon::now()->addMonth(),
                'usage_limit' => 100,
                'is_active' => true,
            ],
            [
                'code' => 'MEAT50',
                'discount' => 50,
                'type' => 'fixed',
                'valid_from' => Carbon::now(),
                'valid_to' => Carbon::now()->addWeek(),
                'usage_limit' => 50,
                'is_active' => true,
            ],
            [
                'code' => 'BBQ10',
                'discount' => 10,
                'type' => 'percent',
                'valid_from' => Carbon::now()->subDay(),
                'valid_to' => Carbon::now()->addDays(5),
                'usage_limit' => null, // Без лимита
                'is_active' => true,
            ],
            [
                'code' => 'SUMMER100',
                'discount' => 100,
                'type' => 'fixed',
                'valid_from' => Carbon::now(),
                'valid_to' => Carbon::now()->addMonth(3),
                'usage_limit' => 20,
                'is_active' => true,
            ],
            [
                'code' => 'INACTIVE',
                'discount' => 15,
                'type' => 'percent',
                'valid_from' => Carbon::now(),
                'valid_to' => Carbon::now()->addMonth(),
                'usage_limit' => 100,
                'is_active' => false, // Неактивный промокод
            ],
            [
                'code' => 'EXPIRED',
                'discount' => 20,
                'type' => 'percent',
                'valid_from' => Carbon::now()->subMonth(2),
                'valid_to' => Carbon::now()->subMonth(),
                'usage_limit' => 100,
                'is_active' => true, // Истекший срок действия
            ],
        ];

        foreach ($promocodes as $promo) {
            Promocode::create($promo);
        }
    }
}