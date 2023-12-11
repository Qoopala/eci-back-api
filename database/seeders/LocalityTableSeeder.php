<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocalityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('localities')->insert([
            ['name' => 'Locality 1', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Locality 2', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Locality 3', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
