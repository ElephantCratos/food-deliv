<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class ManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $manager = User::create([
            'name' => 'manager',
            'email' => 'manager@gmail.com',
            'password' => Hash::make('1234567890'),
            'first_name' => 'John',
            'second_name' => 'Smith',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'email_verified_at' => Carbon::now(),
        ]);

        $managerRole = Role::create([
            'name' => 'manager',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $managerRole->givePermissionTo('access to manager panel', 'access to chat');
        $manager->assignRole('manager');
        $manager->assignRole('support');
    }
}
