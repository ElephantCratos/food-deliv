<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Order;

class UserProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $orders = Order::with(['positions.dish'])
            ->where('customer_id', $user->id)
            ->where('created_at', '>=', now()->subDays(90))
            ->get();

        return view('user_profile', [
            'user' => $user,
            'Orders' => $orders,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->save();

        return back()->with('status', 'Имя успешно обновлено');
    }
}