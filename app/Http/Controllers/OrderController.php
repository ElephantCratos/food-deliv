<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Dish;
use App\Models\OrderPosition;
use App\Enums\OrderStatus;
use Illuminate\Support\Facades\Auth;
use App\Services\PromocodeService;

class OrderController extends Controller
{
    public function showOrders($statusId = null)
{
    $orders = Order::query()
        ->when($statusId, fn($query) => $query->where('status', $statusId))
        ->orderBy('id')
        ->get();

    foreach ($orders as $order) {
        $order->status_name = $order->status->label();
        $order->expected_at = $order->expected_at ?? 'As soon as possible';
    }
    
    return view('All_Orders', ['Order' => $orders]);
}

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
            'time' => 'nullable|date_format:H:i',
        ]);

        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('Cart')->with('error', 'Корзина пуста');
        }

        $positions = collect($cart)->map(function ($item) {
            $dish = Dish::find($item['dish_id']);
            return (object) [
                'dish' => $dish,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ];
        });

        $total = $positions->sum(fn($item) => $item->price * $item->quantity);

        if ($total == 0) {
            return redirect()->route('Cart')->with('error', 'Корзина пуста');
        }

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

        $order = new Order();
        $order->customer_id = Auth::id();
        $order->status = OrderStatus::IN_PROGRESS->value;
        $order->address = $validatedData['adress'];
        $order->comment = $validatedData['comment'];
        $order->expected_at = $request->input('fast_value') == "0" 
            ? $validatedData['time'] 
            : null;
        $order->price = $totalWithDiscount;
        $order->save();

        foreach ($positions as $position) {
            $orderPos = OrderPosition::create([
                'dish_id' => $position->dish->id,
                'price' => $position->price,
                'quantity' => $position->quantity
            ]);
            $order->positions()->attach($orderPos);
        }

        if ($promocode) {
            $promocodeService->incrementUsage($promocode);
        }

        session()->forget('cart');
        session()->forget('promocode');

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
        
        return response()->json(['success' => 'true', 'message' => 'Промокод успешно применен!']);
    }

    public function removePromocode()
    {
        session()->forget('promocode');
        return response()->json(['success' => 'true', 'message' => 'Промокод успешно удалён!']);
    }

    public function showOwnOrders()
    {
        $orders = Order::where('customer_id', Auth::id())
            ->orderByDesc('created_at')
            ->get()
            ->each(function ($order) {
                $order->expected_at = $order->expected_at ?? 'As soon as possible';
            });

        return view('Customer_Orders', ['Orders' => $orders]);
    }

    public function acceptOrder($id)
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => OrderStatus::IN_KITCHEN->value]);

        return back()->with('success', 'Статус заказа успешно обновлен');
    }

    public function declineOrder($id)
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => OrderStatus::DECLINED->value]);

        return back()->with('success', 'Статус заказа успешно обновлен');
    }

    public function declineByCustomer($id)
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => OrderStatus::DECLINED->value]);

        return back()->with('success', 'Статус заказа успешно обновлен');
    }
}