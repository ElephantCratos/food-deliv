<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class CourierAssignmentController extends Controller
{
    public function index()
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

        $couriers = User::role('courier')->get();

        return view('courier_assignment', compact('orders', 'couriers'));
    }

    public function assignCourier(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $order->update([
            'courier_id' => $request->courier_id,
            'status' => OrderStatus::WAITING_FOR_COURIER->value
        ]);

        return back()->with('success', 'Курьер успешно назначен на заказ #'.$order->id);
    }
}