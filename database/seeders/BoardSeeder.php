<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BoardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('boards')->insert([
            [
                'name' => 'Task Management Project for Polytechnic',
                'user_id' => 1,
                'is_public' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Expense Management',
                'user_id' => 2,
                'is_public' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
