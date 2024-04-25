<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Status;
use Illuminate\Support\Facades\Auth;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($Id = null)
    {
        if ($Id) {
            $Order = Order::where('status_id', $Id)->OrderBy('id')->get();
        }else {

        $Order = Order::OrderBy('id')
            ->get();
        }

        $Status = Status::OrderBy('id')
            ->get();

        foreach ($Order as $order) {
        $order->status_name = $Status->where('id', $order->status_id)->first()->name;
    }

       return view('All_Orders',compact([
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
        if ($lastOrder != null && $lastOrder->status_id != null)
        {
            $lastOrder = null;
        }

        $positions = $lastOrder ? $lastOrder->positions : null;




        return view('cart',compact('positions','lastOrder'));
    }

    public function sendOrder()
    {

        $userId = Auth::user()->id;

        $lastOrder = Order::where('customer_id', $userId)
            ->orderBy('created_at', 'desc')
            ->first();

        if ((int)$lastOrder->price == 0)
        {
            redirect()->route('Cart');
        }

        $lastOrder -> status_id = 1;

        $lastOrder->save();

        return redirect()->route('Cart')->with('status','Заказ успешно оформлен');
    }
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
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
        $order->status_id = 7;
        $order->save();

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }

    return redirect()->back()->with('error', 'Order not found.');
}
}
