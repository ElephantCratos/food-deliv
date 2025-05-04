<?php

namespace App\Http\Controllers;

use App\Services\PromocodeService;
use Illuminate\Http\Request;
use App\Models\Dish;
use App\Models\OrderPosition;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderPositionController extends Controller
{
    public function updateQuantity(Request $request, PromocodeService $promocodeService, $dishId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        // 1. Получаем корзину и промокод
        $cart      = session()->get('cart', []);
        $promoCode = session()->get('promocode');

        $dishRemoved = false;
        $itemTotal   = 0;

        // 2. Обновляем количество или удаляем позицию
        foreach ($cart as $key => &$item) {
            if ($item['dish_id'] == $dishId) {
                if ((int)$request->quantity === 0) {
                    unset($cart[$key]);
                    $dishRemoved = true;
                } else {
                    $item['quantity'] = (int)$request->quantity;
                    $itemTotal        = $item['price'] * $item['quantity'];
                }
                break;
            }
        }
        session()->put('cart', $cart);

        // 3. Считаем новый тотал
        $total = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);

        // 4. Применяем промокод, если есть
        $discountAmount    = 0;
        $totalWithDiscount = $total;
        if ($promoCode) {
            $promo = $promocodeService->validate($promoCode);
            if ($promo) {
                // apply возвращает итоговую сумму после скидки
                $totalWithDiscount = $promocodeService->apply($promo, $total);
                $discountAmount    = $total - $totalWithDiscount;
            }
        }

        // 5. Возвращаем ответ
        return response()->json([
            'success'           => true,
            'dishRemoved'       => $dishRemoved,
            'itemTotal'         => $itemTotal,
            'total'             => $total,
            'discountAmount'    => $discountAmount,
            'totalWithDiscount' => $totalWithDiscount,
            'cartCount'         => collect($cart)->sum('quantity'),
        ]);
    }

    

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

        $orderPosition->delete();

        return redirect()->route('Cart')->with('success', 'Блюдо убрано из заказа');

    }
}
