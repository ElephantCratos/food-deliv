<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Dish;
use App\Models\OrderPosition;
use App\Enums\OrderStatus;
use Illuminate\Support\Facades\Auth;
use App\Services\PromocodeService;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
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
    
    $currentTime = Carbon::now(config('app.timezone'))->addHour(); // +1 час

    $timeSlots = [];
    for ($i = 0; $i < 8; $i++) {
        $time = $currentTime->copy()->addMinutes($i * 30);
        $timeSlots[] = $time->format('H:i'); // Добавляем только время
    }

    session(['timeSlots' => $timeSlots]); // Сохраняем слоты в сессии

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
        'totalWithDiscount',
        'timeSlots'
    ));
}

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


    

    public function sendOrder(Request $request, PromocodeService $promocodeService)
{
    $timeSlots = session('timeSlots', []);

    // добавляем значение "null" как валидный вариант
    $allowedTimes = array_merge(['null'], $timeSlots);

    // валидация
    $validatedData = $request->validate([
        'adress'     => ['required','string','max:255'],
        'comment'    => ['nullable','string','max:255'],
        'payment'    => ['required', Rule::in(['card_online','card_courier','cash'])],
        'fast_value' => ['required', Rule::in(['0','1'])],
        'time'       => [
            // если fast_value == 0 — время обязательно
            Rule::requiredIf(fn() => $request->input('fast_value') === '0'),
            'string',
            Rule::in($allowedTimes),       // <— здесь теперь "null" + реальные слоты
        ],
    ]);

    // 3) Проверяем корзину
    $cart = session('cart', []);
    if (empty($cart)) {
        return redirect()->route('Cart')->with('error', 'Корзина пуста');
    }

    // 4) Считаем позиции и сумму
    $positions = collect($cart)->map(fn($item) => (object)[
        'dish'     => Dish::find($item['dish_id']),
        'quantity' => $item['quantity'],
        'price'    => $item['price'],
    ]);
    $total = $positions->sum(fn($p) => $p->price * $p->quantity);
    if ($total == 0) {
        return redirect()->route('Cart')->with('error', 'Корзина пуста');
    }

    // 5) Промокод
    $promocode       = null;
    $discountAmount  = 0;
    $totalWithDiscount = $total;
    if (session('promocode')) {
        $promocode = $promocodeService->validate(session('promocode'));
        if ($promocode) {
            $totalWithDiscount = $promocodeService->apply($promocode, $total);
            $discountAmount    = $total - $totalWithDiscount;
        }
    }

    // 6) Создаём заказ
    $order = new Order();
    $order->customer_id = Auth::id();
    $order->status      = OrderStatus::IN_PROGRESS->value;
    $order->address     = $validatedData['adress'];
    $order->comment     = $validatedData['comment'] ?? null;
    // если fast_value = 0, значит выбран слот
    $order->expected_at = $validatedData['fast_value'] === '0'
        ? $validatedData['time']
        : null;
    $order->price       = $totalWithDiscount;
    $order->save();

    // 7) Позиции
    foreach ($positions as $pos) {
        $orderPos = OrderPosition::create([
            'dish_id'  => $pos->dish->id,
            'price'    => $pos->price,
            'quantity' => $pos->quantity,
        ]);
        $order->positions()->attach($orderPos);
    }

    if ($promocode) {
        $promocodeService->incrementUsage($promocode);
    }

    // 8) Чистим сессию и редиректим
    session()->forget(['cart','promocode']);
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