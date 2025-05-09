<?php

namespace App\Models;

use App\Enums\OrderStatus;
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
        'status',
        'address',
        'comment',
        'expected_at'
    ];

    protected $casts = [
        'status' => OrderStatus::class,
    ];

    // Связи
    public function positions()
    {
        return $this->belongsToMany(OrderPosition::class);
    }

    public function courier()
    {
        return $this->belongsTo(User::class, 'courier_id');
    }

    public function promocode()
    {
        return $this->belongsTo(Promocode::class);
    }

    // Scope для заказов курьера
    public function scopeAvailableForCourier($query, int $userId)
    {
        return $query
            ->whereIn('status', [
                OrderStatus::WAITING_FOR_COURIER->value,
                OrderStatus::COURIER_ON_THE_WAY->value
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
        return $this->expected_at ?? 'Как можно скорее';
    }

    // Проверка, можно ли принять заказ
    public function canBeAccepted(): bool
    {
        return $this->status_id === OrderStatus::WAITING_FOR_COURIER;
    }

    public function scopePending($query)
    {
        return $query->where('status', OrderStatus::IN_PROGRESS->value);
    }

    // Scope для заказов в работе
    public function scopeInProcess($query)
    {
        return $query->whereIn('status', [
            OrderStatus::IN_PROGRESS->value,
            OrderStatus::IN_KITCHEN->value,
            OrderStatus::WAITING_FOR_COURIER->value
        ]);
    }

    // Проверка можно ли отменить заказ
    public function canBeDeclined(): bool
    {
        return in_array($this->status_id, [
            OrderStatus::IN_PROGRESS->value,
            OrderStatus::IN_KITCHEN->value
        ]);
    }
}