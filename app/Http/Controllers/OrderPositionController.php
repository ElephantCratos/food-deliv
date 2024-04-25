<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingredient;
use App\Models\Dish;
use App\Models\OrderPosition;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderPositionController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

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

        $selectedIngredients = $request->all();

        $ingredientIds = [];

        foreach ($selectedIngredients as $key => $value) {
            if (str_starts_with($key, 'topping')) {
                $ingredientIds[] = $value;
            }
        }


        $ingredients = Ingredient::whereIn('id', $ingredientIds)->get();



        $OrderPosition->ingredients()->attach($ingredients);


        $userId = Auth::id();

        $lastOrder = Order::where('customer_id', $userId)
            ->orderBy('created_at', 'desc')
            ->first();


        if (empty($lastOrder) || $lastOrder->status_id != null) {
            
            $newOrder = new Order();
            $newOrder->customer_id = $userId;
            $newOrder->status_id = null;
            $newOrder->courier_id = null;
            $newOrder->price = $dish->price;

            $newOrder->save();

            $newOrder->positions()->attach($OrderPosition);

        }
        else
        {
            $lastOrder->price+=$dish->price;
            $lastOrder->positions()->attach($OrderPosition);
        }



        return redirect()->route('catalog')->with('success', 'OrderPosition created successfully.');
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
    public function update(Request $request)
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
}
