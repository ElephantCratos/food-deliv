<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;

class UserProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $orders = Order::with(['positions.dish'])
            ->where('customer_id', $user->id)
            ->where('created_at', '>=', now()->subDays(90))
            ->orderByDesc('created_at')
            ->get();

        return view('user_profile', [
            'user'   => $user,
            'orders' => $orders, 
        ]);
    }


    public function update(Request $request)
    {
       
            $user = Auth::user();
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['nullable', 'string', 'size:12'],
                'birthdate' => ['nullable', 'date_format:d.m.Y'],
                'notifications_enabled' => ['nullable', 'boolean'],
            ]);
        
            $birthdate = $validated['birthdate'] ;
                
            // dd($validated['birthdate'],$request->birthdate,$birthdate);
            $user->name = $validated['name'];
            $user->phone = $validated['phone'] ?? null;
            $user->birthdate = $birthdate;
            $user->notifications_enabled = $request->has('notifications_enabled');
        
            $user->save();

        
            return back()->with('status', 'Данные успешно обновлены!');
   
    }
}