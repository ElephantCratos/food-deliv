<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Order; // Импортируем модель Order

class UserProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Получаем заказы пользователя (если статус просто поле, убираем 'status' из with)
        $orders = Order::with(['positions.dish']) // Если статус - это поле, убираем 'status'
            ->where('customer_id', $user->id) // Проверка на customer_id
            ->where('created_at', '>=', now()->subDays(90)) // Получаем заказы за последние 90 дней
            ->get();

        return view('user_profile', [
            'user' => $user,
            'Orders' => $orders, // Передаем заказы в шаблон
        ]);
    }
}
