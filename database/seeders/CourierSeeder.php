<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CourierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courier = User::create([
            'name' => 'courier',
            'email' => 'courier@gmail.com',
            'password' => Hash::make('1234567890'),
            'first_name' => 'Usain',
            'second_name' => 'Bolt',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'email_verified_at' => Carbon::now(),
        ]);

        $courierRole = Role::create([
            'name' => 'courier',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $courierRole->givePermissionTo('access to courier panel', 'access to chat');
        $courier->assignRole('courier');
    }
}