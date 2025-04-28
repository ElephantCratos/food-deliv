<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;

class KitchenController extends Controller
{
    public function showOrdersToKitchen()
    {
        $orders = Order::whereIn('status', [
                OrderStatus::IN_KITCHEN->value,
                OrderStatus::WAITING_FOR_COURIER->value
            ])
            ->orderBy('id')
            ->get()
            ->each(function ($order) {
                $order->expected_at_formatted = $order->expected_at ?? 'As soon as possible';
            });
        return view('Kitchen_Orders', ['orders' => $orders]);
    }

    public function markAsReady($id): RedirectResponse
    {
        $order = Order::findOrFail($id);
        $order->update([
            'status' => OrderStatus::WAITING_FOR_COURIER->value,
        ]);

        return back()->with('success', 'Заказ готов к передаче курьеру');
    }
}