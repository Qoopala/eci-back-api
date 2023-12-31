<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfficeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('offices')->insert([
            [
                'name' => 'EC Inmobiliarios Esplugues S.L.',
                'cif' => 'B-44636587',
                'address' => 'Av. Cornellà, 113, local 4, 08950 Esplugues de Llobregat',
                'map' => 'Map coordinates for Office 1',
                'email' => 'info@ebusa.es',
                'phone' => '931373749',
                'feature' => 'necesita update',
                'locality_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'EC Inmobiliarios Gavarra S.L.',
                'cif' => 'B-44636504',
                'address' => 'Calle La Miranda, 41-43, local 4, 08940 Cornellà de Llobregat',
                'map' => 'Map coordinates for Office 2',
                'email' => 'info@ebusa.es',
                'phone' => '931389669',
                'feature' => 'necesita update',
                'locality_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ebusa Consultores Inmobiliarios S.L.',
                'cif' => 'B-67336958',
                'address' => 'Av. Sant Ildefons 8, local 6, 08940 Cornellà de Llobregat',
                'map' => 'Map coordinates for Office 3',
                'email' => 'info@ebusa.es',
                'phone' => '931564980',
                'feature' => 'necesita update',
                'locality_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
