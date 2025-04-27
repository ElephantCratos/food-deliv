<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;

// ОТРЕФАКТОРЕН, НО НАДО ПРОСМОТРЕТЬ ПО УТАТУСАМ. ЕСЛИ ОТКАЗЫВАЕМСЯ - ПЕРЕДЕЛКА.
class KitchenController extends Controller
{
    public function showOrdersToKitchen()
    {
        $orders = Order::whereIn('status_id', [
                Order::STATUS_AWAITING_ACCEPTANCE,
                Order::STATUS_IN_KITCHEN,
                Order::STATUS_WAITING_FOR_COURIER
            ])
            ->with('status')
            ->orderBy('id')
            ->get();

        return view('Kitchen_Orders', [
            'orders' => $orders->map(function ($order) {
                $order->expected_at_formatted = $order->expected_at ?? 'As soon as possible';
                return $order;
            })
        ]);
    }

    public function confirmPreparation(Order $order): RedirectResponse
    {
        $order->update([
            'status_id' => Order::STATUS_IN_KITCHEN,
        ]);
        $order->save;
        dd($order->status_id, Order::STATUS_IN_KITCHEN);
        return back()->with('success', 'Подтверждено начало приготовления заказа');
    }

    public function transferToCourier(Order $order): RedirectResponse
    {
        $order->update([
            'status_id' => Order::STATUS_WAITING_FOR_COURIER,
           
        ]);

        return back()->with('success', 'Заказ передан курьеру');
    }

    public function courierArrived(Order $order): RedirectResponse
    {
        $order->update([
            'status_id' => Order::STATUS_GIVEN_TO_COURIER,
            
        ]);

        return back()->with('success', 'Курьер принял заказ');
    }
}