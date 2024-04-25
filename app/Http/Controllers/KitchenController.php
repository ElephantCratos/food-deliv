<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Status;

class KitchenController extends Controller
{
    public function index()
    {

        $orders = Order::whereIn('status_id', [2, 3, 4])
            ->orderBy('id')
            ->get();

        return view('Kitchen_Orders', compact('orders'));
    }

    public function confirmPreparation($id)
    {
        $order = Order::find($id);

        if ($order) {
            // "Ожидает курьера"
            $order->status_id = 3;
            $order->save();

            return redirect()->back()->with('success', 'Order preparation confirmed successfully.');
        }

        return redirect()->back()->with('error', 'Order not found.');
    }


    public function transferToCourier($id)
    {
        $order = Order::find($id);

        if ($order) {
            // "В пути"
            $order->status_id = 4;
            $order->save();

            return redirect()->back()->with('success', 'Order transferred to courier successfully.');
        }

        return redirect()->back()->with('error', 'Order not found.');
    }


    public function courierArrived($id)
    {

        $order = Order::find($id);

        if ($order) {
            // "В пути"
            $order->status_id = 5;
            $order->save();

            return redirect()->back()->with('success', 'Order transferred to courier successfully.');
        }

        return redirect()->back()->with('error', 'Order not found.');
    }
}
