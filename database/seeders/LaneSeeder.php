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
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'In Progress',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Done',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
