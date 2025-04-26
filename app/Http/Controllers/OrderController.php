<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Dish;
use App\Models\Status;
use Illuminate\Contracts\Support\ValidatedData;
use Illuminate\Support\Facades\Auth;
use App\Services\PromocodeService;


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

     public function showCart(PromocodeService $promocodeService)
{
    $cart = session('cart', []);

    $positions = collect($cart)->map(function ($item) {
        $dish = Dish::find($item['dish_id']);
        return (object) [
            'dish' => $dish,
            'quantity' => $item['quantity'],
            'price' => $item['price'],
        ];
    });

    $total = $positions->sum(fn($item) => $item->price * $item->quantity);

    // Промокод
    $promocode = null;
    $discountAmount = 0;
    $totalWithDiscount = $total;

    if (session('promocode')) {
        $promocode = $promocodeService->validate(session('promocode'));
        if ($promocode) {
            $totalWithDiscount = $promocodeService->apply($promocode, $total);
            $discountAmount = $total - $totalWithDiscount;
        }
    }

    return view('cart', compact(
        'positions',
        'promocode',
        'discountAmount',
        'totalWithDiscount'
    ));
}

 

    public function sendOrder(Request $request, PromocodeService $promocodeService)
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

        // Применение промокода
        if (session('promocode')) {
            $promocode = $promocodeService->validate(session('promocode'));
            
            if ($promocode) {
                $newTotal = $promocodeService->apply($promocode, $lastOrder->price);
                $discountAmount = $lastOrder->price - $newTotal;
                
                // Обновляем заказ
                $lastOrder->price = $newTotal;
                $lastOrder->discount = $discountAmount;
                $lastOrder->promocode_id = $promocode->id;
                
                // Увеличиваем счетчик использований
                $promocodeService->incrementUsage($promocode);
            }
            
            // Очищаем промокод из сессии
            session()->forget('promocode');
        }

        // Обновление данных заказа
        $lastOrder->status_id = 1;
        $lastOrder->address = $validatedData['adress'];
        $lastOrder->comment = $validatedData['comment'];
        $lastOrder->expected_at = $request->input('fast_value') == "0" 
            ? $validatedData['time'] 
            : null;

        $lastOrder->save();

        return redirect()->route('Cart')->with('status', 'Заказ успешно оформлен');
    }

    public function applyPromocode(Request $request, PromocodeService $promocodeService)
    {
        $request->validate(['promocode' => 'required|string']);
        
        $promocode = $promocodeService->validate($request->promocode);
        
        if (!$promocode) {
            return back()->with('error', 'Промокод недействителен или истек');
        }
        
        session(['promocode' => $promocode->code]);
        
        return back()->with('success', 'Промокод успешно применен!');
    }

    public function removePromocode()
    {
        session()->forget('promocode');
        return back()->with('status', 'Промокод удален');
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
