<?php

namespace Database\Seeders;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'access to manager panel']);
        Permission::create(['name' => 'access to kitchen panel']);
        Permission::create(['name' => 'access to courier panel']);
        Permission::create(['name' => 'access to chat']);
        Permission::create(['name' => 'access to user panel']);
    }
}
