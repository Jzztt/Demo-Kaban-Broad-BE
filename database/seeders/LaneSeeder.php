<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LaneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('lanes')->insert([
            [
                'name' => 'To Do',
                'board_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'In Progress',
                'board_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Done',
                'board_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
