<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;


class KitchenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kitchen = User::create([
            'name' => 'kitchen',
            'email' => 'kitchen@gmail.com',
            'password' => Hash::make('1234567890'),
            'first_name' => 'Gordon',
            'second_name' => 'Ramsay',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'email_verified_at' => Carbon::now(),
            'phone' => '+79900199422', // Добавьте сюда номер телефона
        ]);

        $kitchenRole = Role::create([
            'name' => 'kitchen',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $kitchenRole->givePermissionTo('access to kitchen panel');
        $kitchen->assignRole('kitchen');
    }
}
