<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'first_name' => ['required', 'string', 'max:30'],
        'second_name' => ['required', 'string', 'max:30'],
        'phone' => ['required', 'string', 'regex:/^\+?\d{1,4}?[\d\s\-\(\)]{3,20}$/'],
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'first_name' => $request->first_name,
        'second_name' => $request->second_name,
        'phone' => $request->phone,
    ]);

    $user->assignRole('user');
    event(new Registered($user));
    Auth::login($user);

    session()->flash('show_welcome', true);

    if ($request->ajax()) {
        return response()->json(['success' => true, 'redirect' => route('dashboard')]);
    }

    return redirect(route('catalog', absolute: false));
}
}
