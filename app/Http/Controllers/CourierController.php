<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;


// ОТРЕФАКТОРЕН, НО НАДО ПРОСМОТРЕТЬ ПО СТАТУСАМ И ПРОЧЕЙ ШЕЛУПОНИ

class CourierController extends Controller
{
    // Список заказов для курьера
    public function showOrdersToCourier()
    {
        $orders = Order::availableForCourier(Auth::id())->get();
        return view('Courier_Orders', compact('orders'));
    }

    // Принять заказ
    public function acceptOrder(Order $order)
    {
        if (!$order->canBeAccepted()) {
            return back()->with('error', 'Заказ нельзя принять в текущем статусе');
        }

        if ($order->courier_id && $order->courier_id !== Auth::id()) {
            return back()->with('error', 'Заказ уже взят другим курьером');
        }

        $order->update([
            'courier_id' => Auth::id(),
            'status_id' => Order::STATUS_GIVEN_TO_COURIER
        ]);

        return back()->with('success', 'Заказ успешно принят');
    }

    // Подтвердить доставку
    public function confirmDelivery(Order $order)
    {
        if ($order->courier_id !== Auth::id()) {
            return back()->with('error', 'Это не ваш заказ');
        }

        $order->update([
            'status_id' => Order::STATUS_COMPLETED,
            'delivered_at' => now()
        ]);

        return back()->with('success', 'Доставка подтверждена');
    }

    // Отменить заказ
    public function declineOrder(Order $order)
    {
        if ($order->courier_id !== Auth::id()) {
            return back()->with('error', 'Это не ваш заказ');
        }

        $order->update([
            'status_id' => Order::STATUS_DECLINED,
            'courier_id' => null
        ]);

        return back()->with('success', 'Заказ отклонён');
    }
}