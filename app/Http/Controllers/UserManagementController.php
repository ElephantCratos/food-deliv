<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::role(['manager', 'courier'])->get();
        return view('user-management.index', compact('users'));
    }

    public function create()
    {
        return view('user-management.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'email', 'max:255', 'unique:users'],
            'phone'          => [
                'required',
                'string',
                'regex:/^\+?\d{1,4}?[\d\s\-\(\)]{3,20}$/'
            ],
            'password'       => ['required', 'confirmed', Rules\Password::defaults()],
            'first_name'     => ['required', 'string', 'max:30'],
            'second_name'    => ['required', 'string', 'max:30'],
            'role'           => ['required', 'in:manager,courier'],
        ]);

        $user = User::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'password'     => Hash::make($request->password),
            'first_name'   => $request->first_name,
            'second_name'  => $request->second_name,
        ]);

        $user->assignRole($request->role);

        if ($request->role === 'manager') {
            $user->assignRole('support'); // добавляем вторую роль
        }

        return redirect()
            ->route('user-management.index')
            ->with('success', 'Пользователь создан');
    }

    public function destroy(User $user)
    {
        if ($user->hasRole(['manager', 'courier'])) {
            $user->delete();
        }

        return redirect()
            ->route('user-management.index')
            ->with('success', 'Пользователь удалён');
    }
}
