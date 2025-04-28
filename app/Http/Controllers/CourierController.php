<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class CourierController extends Controller
{
    // Список заказов для курьера
    public function showOrdersToCourier()
    {
        $user = auth()->user();
    
        $orders = Order::where('courier_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->each(function ($order) {
                $order->expected_at_formatted = $order->expected_at ?? 'As soon as possible';
            });
    
        return view('Courier_Orders', [
            'orders' => $orders,
            'courier' => $user
        ]);
    }

    // Принять заказ
    public function acceptOrder($id)
    {
        $order = Order::findOrFail($id);
        
        $order->update([
            'status' => OrderStatus::COURIER_ON_THE_WAY->value
        ]);

        return back()->with('success', 'Заказ успешно принят');
    }

    // Подтвердить доставку
    public function confirmDelivery($id)
    {
        $order = Order::findOrFail($id);

        $order->update([
            'status' => OrderStatus::COMPLETED->value,
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
            'status' => OrderStatus::DECLINED->value,
            'courier_id' => null
        ]);

        return back()->with('success', 'Заказ отклонён');
    }
}