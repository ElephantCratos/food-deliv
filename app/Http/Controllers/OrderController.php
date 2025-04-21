<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Status;
use Illuminate\Contracts\Support\ValidatedData;
use Illuminate\Support\Facades\Auth;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function showOrders($Id = null)
    {
        if ($Id) {
            $Order = Order::where('status_id', $Id)->OrderBy('id')->get();
        } else {

            $Order = Order::OrderBy('id')
                ->get();
        }

        $Status = Status::OrderBy('id')
            ->get();

        foreach ($Order as $order) {
            $order->status_name = $Status->where('id', $order->status_id)->first()->name;
        }

        foreach ($Order as $order) {
            if ($order->expected_at === null) {
                $order->expected_at = 'As soon as possible';
            }
        }

        return view('All_Orders', compact([
            'Order'
        ]));
    }

    /**
     * Show the form for creating a new resource.
     */

    public function showCart()
    {
        $userId = Auth::user()->id;


        $lastOrder = Order::where('customer_id', $userId)
            ->orderBy('created_at', 'desc')
            ->first();
        if ($lastOrder != null && $lastOrder->status_id != null) {
            $lastOrder = null;
        }

        $positions = $lastOrder ? $lastOrder->positions : null;

        return view('cart', compact('positions', 'lastOrder'));
    }

    public function sendOrder(Request $request)
    {

        $validatedData = $request->validate([
            'adress' => 'required|string|max:255',
            'comment' => 'nullable|string|max:255',
            'time' => '|date_format:H:i|',

        ]);

        $userId = Auth::user()->id;

        $lastOrder = Order::where('customer_id', $userId)
            ->orderBy('created_at', 'desc')
            ->first();

        if ((int)$lastOrder->price == 0) {

            return redirect()->route('Cart');
        }

        $lastOrder->status_id = 1;
        $lastOrder->address = $validatedData['adress'];
        $lastOrder->comment = $validatedData['comment'];

        if ($request->input('fast_value') == "0") {
            $lastOrder->expected_at = $validatedData['time'];
        } else {
            $lastOrder->expected_at = null;
        }

        $lastOrder->save();

        return redirect()->route('Cart')->with('status', 'Заказ успешно оформлен');
    }

    function showOwnOrders()
    {
        $userId = Auth::user()->id;

        $Orders = Order::where('customer_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($Orders as $order) {
        if ($order->expected_at === null) {
            $order->expected_at = 'As soon as possible';
        }
    }

        return view('Customer_Orders', compact( 'Orders'));
    }

    public function acceptOrder($id)
    {
        $order = Order::find($id);

        if ($order) {
            $order->status_id = 2;
            $order->save();

            return redirect()->back()->with('success', 'Order status updated successfully.');
        }

        return redirect()->back()->with('error', 'Order not found.');
    }

    public function declineOrder($id)
    {
        $order = Order::find($id);

        if ($order) {
            $order->status_id = 8;
            $order->save();

            return redirect()->back()->with('success', 'Order status updated successfully.');
        }

        return redirect()->back()->with('error', 'Order not found.');
    }

    public function declineByCustomer($id)
    {
        $order = Order::find($id);

        if ($order) {
            $order->status_id = 9;
            $order->save();

            return redirect()->back()->with('success', 'Order status updated successfully.');
        }

        return redirect()->back()->with('error', 'Order not found.');
    }
}
