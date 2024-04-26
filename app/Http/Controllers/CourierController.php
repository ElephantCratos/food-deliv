<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class CourierController extends Controller
{
    public function index()
    {
        $userId = Auth::user()->id;

        $orders = Order::whereIn('status_id', [3, 4, 5, 6])
            ->where(function ($query) use ($userId) {
                $query->where('courier_id', $userId)
                    ->orWhereNull('courier_id');
            })
            ->orderBy('id')
            ->get();
        foreach ($orders as $order) {
            if ($order->expected_at === null) {
                $order->expected_at = 'As soon as possible';
            }
        }

        return view('Courier_Orders', compact('orders'));
    }

    public function confirmDelivery($id)
    {
        $order = Order::find($id);

        if ($order) {
            $order->status_id = 6;
            $order->save();

            return redirect()->back()->with('success', 'Delivery confirmed successfully.');
        }

        return redirect()->back()->with('error', 'Order not found.');
    }

    public function orderHasDelivered($id)
    {
        $order = Order::find($id);

        if ($order) {
            $order->status_id = 7;
            $order->save();

            return redirect()->back()->with('success', 'Delivered');
        }

        return redirect()->back()->with('error', 'Order not found.');
    }

    public function AcceptOrder($id)
    {
        $order = Order::find($id);

        if ($order) {
            $order->courier_id= Auth::user()->id;
            $order->save();

            return redirect()->back()->with('success', 'Заказ был закреплен за вами');
        }
            return redirect()->back()->with('success', 'Произошла ошибка');
    }
}
