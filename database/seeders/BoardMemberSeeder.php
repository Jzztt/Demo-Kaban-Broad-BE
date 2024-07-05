<?php

namespace Database\Seeders;

use App\Models\Board;
use App\Models\BoardMember;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BoardMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $boards = Board::all();

        foreach ($boards as $board) {
            BoardMember::create([
                'user_id' => 1,
                'board_id' => $board->id,
            ]);

            BoardMember::create([
                'user_id' => 2,
                'board_id' => $board->id,
            ]);
        }
    }
}
