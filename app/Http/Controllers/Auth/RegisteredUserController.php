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
    public function store(Request $request): RedirectResponse
    {
        // Добавлена валидация, чтобы гарантировать правильный формат номера телефона
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'first_name' => ['required', 'string', 'max:30'],
            'second_name' => ['required', 'string', 'max:30'],
            'phone' => ['required', 'string', 'regex:/^\+?\d{1,4}?[\d\s\-\(\)]{3,20}$/'], // Валидация для телефона
        ]);

        // Создание нового пользователя
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'first_name' => $request->first_name,
            'second_name' => $request->second_name,
            'phone' => $request->phone,  // Сохраняем номер телефона
        ]);

        // Присваиваем роль пользователю
        $user->assignRole('user');

        // Генерируем событие для регистрации
        event(new Registered($user));

        // Логиним пользователя
        Auth::login($user);

        // Перенаправляем на страницу с информацией о пользователе
        return redirect(route('dashboard', absolute: false));
    }
}
