<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Константы статусов (соответствуют StatusSeeder)
    const STATUS_IN_PROGRESS = 1;
    const STATUS_AWAITING_ACCEPTANCE = 2;
    const STATUS_IN_KITCHEN = 3;
    const STATUS_WAITING_FOR_COURIER = 4;
    const STATUS_GIVEN_TO_COURIER = 5;
    const STATUS_COURIER_ON_THE_WAY = 6;
    const STATUS_COMPLETED = 7;
    const STATUS_DECLINED = 8;
    const STATUS_DECLINED_BY_CUSTOMER = 9;

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

    // Связи
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

    // Scope для заказов курьера
    public function scopeAvailableForCourier($query, int $userId)
    {
        return $query
            ->whereIn('status_id', [
                self::STATUS_WAITING_FOR_COURIER,
                self::STATUS_GIVEN_TO_COURIER,
                self::STATUS_COURIER_ON_THE_WAY
            ])
            ->where(function ($query) use ($userId) {
                $query->where('courier_id', $userId)
                    ->orWhereNull('courier_id');
            })
            ->with(['status', 'positions'])
            ->orderBy('id');
    }

    // Форматирование даты
    public function getExpectedAtFormattedAttribute(): string
    {
        return $this->expected_at ?? 'As soon as possible';
    }

    // Проверка, можно ли принять заказ
    public function canBeAccepted(): bool
    {
        return $this->status_id === self::STATUS_WAITING_FOR_COURIER;
    }

    public function scopePending($query)
    {
        return $query->where('status_id', self::STATUS_IN_PROGRESS);
    }

    // Scope для заказов в работе
    public function scopeInProcess($query)
    {
        return $query->whereIn('status_id', [
            self::STATUS_AWAITING_ACCEPTANCE,
            self::STATUS_IN_KITCHEN,
            self::STATUS_WAITING_FOR_COURIER
        ]);
    }

    // Проверка можно ли отменить заказ
    public function canBeDeclined(): bool
    {
        return in_array($this->status_id, [
            self::STATUS_IN_PROGRESS,
            self::STATUS_AWAITING_ACCEPTANCE
        ]);
    }
}