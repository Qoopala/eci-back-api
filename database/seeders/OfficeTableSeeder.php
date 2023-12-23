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
                'name' => 'Oficina ECI en Sant Ildefons',
                'cif' => 'B67336958',
                'address' => 'Av. Sant Ildefons 8, Local 6',
                'map' => 'Map coordinates for Office 1',
                'email' => 'info@ebusa.es',
                'phone' => '931 564 980',
                'path_image' => 'office1.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Oficina ECI en Sant Ildefons',
                'cif' => 'B67336958',
                'address' => '456 Oak Avenue',
                'map' => 'Map coordinates for Office 2',
                'email' => 'office2@example.com',
                'phone' => '987-654-3210',
                'path_image' => 'office2.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Office 3',
                'cif' => 'B67336958',
                'address' => '789 Pine Road',
                'map' => 'Map coordinates for Office 3',
                'email' => 'office3@example.com',
                'phone' => '555-123-4567',
                'path_image' => 'office3.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
