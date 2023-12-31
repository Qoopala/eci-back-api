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
            ['id' => 1, 'name' => 'Cornellà de Llobregat', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Esplugues de Llobregat', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('sublocalities')->insert([
            ['name' => 'Sant Ildefons', 'locality_id' => 1 , 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'La Gavarra', 'locality_id' => 1 , 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cornellà Centre', 'locality_id' => 1 , 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Riera', 'locality_id' => 1 , 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'El Pedró', 'locality_id' => 1 , 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Almeda', 'locality_id' => 1 , 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Fontsanta-Fatjó', 'locality_id' => 1 , 'created_at' => now(), 'updated_at' => now()],

            ['name' => 'La Plana - Montesa', 'locality_id' => 2 , 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Can Vidalet', 'locality_id' => 2 , 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'La Miranda - Ciutat Diagonal', 'locality_id' => 2 , 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Finestrelles', 'locality_id' => 2 , 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Esplugues Centre', 'locality_id' => 2 , 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Can Clota', 'locality_id' => 2 , 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'El Gall', 'locality_id' => 2 , 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'La Mallola', 'locality_id' => 2 , 'created_at' => now(), 'updated_at' => now()],

        ]);
    }
}
