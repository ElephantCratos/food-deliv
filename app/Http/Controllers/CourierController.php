<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class CourierController extends Controller
{
    public function index()
    {
        $orders = Order::where('status_id', 3)->orderBy('id')->get();

        return view('Courier_Orders', compact('orders'));
    }

    public function confirmDelivery($id)
    {
        $order = Order::find($id);

        if ($order) {
            // "Доставлен"
            $order->status_id = 5;
            $order->save();
            
            return redirect()->back()->with('success', 'Delivery confirmed successfully.');
        }

        return redirect()->back()->with('error', 'Order not found.');
    }
}
