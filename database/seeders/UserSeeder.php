<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'user',
            'email' => 'user@gmail.com',
            'password' => Hash::make('1234567890'),
            'first_name' => 'Ivan',
            'second_name' => 'Ivanovich',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'email_verified_at' => Carbon::now(),
            'phone' => '+79900199412', // Добавьте сюда номер телефона
        ]);

        $userRole = Role::create([
            'name' => 'user',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $user->assignRole('user');
    }
}
