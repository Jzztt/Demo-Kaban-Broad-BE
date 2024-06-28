<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tickets')->insert([
            [
                'lane_id' => 3,
                'title' => 'Deploy version 2.1 to production',
                'author' => 'John Doe',
                'created_at' => now(),
                'priority' => 'High',
                'comments_count' => 0,
                'position' => 0,
                'description' => 'Description of the first ticket',
                'link_issue' => 'http://example.com/issue1'
            ],
            [
                'lane_id' => 2,
                'title' => 'Update database schema',
                'author' => 'Jane Doe',
                'created_at' => now(),
                'priority' => 'Medium',
                'comments_count' => 0,
                'position' => 0,
                'description' => 'Description of the second ticket',
                'link_issue' => 'http://example.com/issue2'
            ],
            [
                'lane_id' => 1,
                'title' => 'Add new feature',
                'author' => 'Michael Smith',
                'created_at' => now(),
                'priority' => 'Low',
                'comments_count' => 0,
                'position' => 0,
                'description' => 'Description of the third ticket',
                'link_issue' => 'http://example.com/issue3'
            ],
        ]);
    }
}
