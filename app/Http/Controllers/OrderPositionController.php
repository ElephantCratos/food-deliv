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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'dish_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);
    
        $dish = Dish::find($request->dish_id);
        if (!$dish) {
            return redirect()->back()->with('error', 'Блюдо не найдено.');
        }
    
        $cart = session()->get('cart', []);
    
        // Если такое блюдо уже есть — увеличиваем количество
        $found = false;
        foreach ($cart as &$item) {
            if ($item['dish_id'] == $dish->id) {
                $item['quantity'] += $request->quantity;
                $found = true;
                break;
            }
        }
        if (!$found) {
            $cart[] = [
                'dish_id' => $dish->id,
                'price' => $dish->price,
                'quantity' => $request->quantity,
            ];
        }
    
        session()->put('cart', $cart);
    
        return redirect()->route('catalog')->with('success', 'Блюдо добавлено в корзину.');
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


        $orderPosition->ingredients()->detach();

        $orderPosition->delete();




        return redirect()->route('Cart')->with('success', 'Блюдо убрано из заказа');

    }
}
