<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $status = Status::create([
            'id' => '1',
            'name' => 'in progress',
        ]);

        $status = Status::create([
            'id' => '2',
            'name' => 'awaiting acceptance into the kitchen',
        ]);

        $status = Status::create([
            'id' => '3',
            'name' => 'in the kitchen',
        ]);

        $status = Status::create([
            'id' => '4',
            'name' => 'Waiting for the courier',
        ]);

        $status = Status::create([
            'id' => '5',
            'name' => 'The courier is on the way',
        ]);

        $status = Status::create([
            'id' => '6',
            'name' => 'completed',
        ]);
    }
}