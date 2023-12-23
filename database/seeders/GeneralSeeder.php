<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GeneralSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin@mail.com',
                'password' => '$2y$12$BDb2.OTsBseULOqCl4XM1eQZL/OY8nGANgI3nlTGSNdNj2It32nA2',
                'created_at' => now(), 
                'updated_at' => now()
            ],
        ]);

        DB::table('categories')->insert([
            ['name' => 'Category 1', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Category 2', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Category 3', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
