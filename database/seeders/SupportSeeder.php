<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SupportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $support = User::create([
            'name' => 'Support',
            'email' => 'support@gmail.com',
            'password' => Hash::make('1234567890'),
            'first_name' => 'Support',
            'second_name' => 'Support',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'email_verified_at' => Carbon::now(),
        ]);

        $supportRole = Role::create([
            'name' => 'support',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        // Пермишены накидываются все, просто 4test. 
        // Это не пользак не для того, чтобы работать с ним залогиненным, а просто системный для чата
        // Если что потом переделать можно, не паревно.
        $permissions = Permission::pluck('name')->all();
        $supportRole->syncPermissions($permissions);
        $support->assignRole('support');
    }
}
