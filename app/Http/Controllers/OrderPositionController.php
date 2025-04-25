<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dish;
use App\Models\OrderPosition;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderPositionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $id = $request->input('dish_id');
        $dish = Dish::findOrFail($id);
        $request->validate([
            'quantity'=>'required|numeric|min:1',
        ]);

        $OrderPosition = OrderPosition::create([
            'dish_id' => $id,
            'price' => $dish->price,
            'quantity' => $request->quantity,
        ]);

        $userId = Auth::id();

        $lastOrder = Order::where('customer_id', $userId)
            ->orderBy('created_at', 'desc')
            ->first();


        if (empty($lastOrder) || $lastOrder->status_id != null) {

            $newOrder = new Order();
            $newOrder->customer_id = $userId;
            $newOrder->status_id = null;
            $newOrder->courier_id = null;

            $dishPrice = (float) $dish->price;
            $dishQuantity = $OrderPosition->quantity;

            $priceIncrease = $dishPrice * $dishQuantity;


            $newOrder->price = $newOrder->price + $priceIncrease;

            $newOrder->save();

            $newOrder->positions()->attach($OrderPosition);

        }
        else
        {
            $dishPrice = (float) $dish->price;
            $dishQuantity = $OrderPosition->quantity;

            $priceIncrease = $dishPrice * $dishQuantity;


            $lastOrder->price = $lastOrder->price + $priceIncrease;


            $lastOrder->positions()->attach($OrderPosition);
            $lastOrder->save();


        }



        return redirect()->route('catalog')->with('success', 'OrderPosition created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $orderPosition = OrderPosition::findOrFail($id);

        $userId = Auth::id();

        $lastOrder = Order::where('customer_id', $userId)
            ->orderBy('created_at', 'desc')
            ->first();


        $dish = Dish::findOrFail($orderPosition->dish_id);

        $lastOrder->price -= (float)$dish->price * (float)$orderPosition->quantity;
        $lastOrder->save();

        $orderPosition->delete();

        return redirect()->route('Cart')->with('success', 'Блюдо убрано из заказа');

    }
}
