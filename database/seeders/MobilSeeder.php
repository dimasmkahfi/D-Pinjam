<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MobilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mobils = [
            [
                'merk' => 'Toyota',
                'model' => 'Avanza',
                'plat_nomor' => 'B 1234 ABC',
                'warna' => 'Hitam',
                'tahun' => 2020,
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'merk' => 'Honda',
                'model' => 'Brio',
                'plat_nomor' => 'D 5678 XYZ',
                'warna' => 'Merah',
                'tahun' => 2019,
                'status' => 'disewa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'merk' => 'Suzuki',
                'model' => 'Ertiga',
                'plat_nomor' => 'F 1122 GH',
                'warna' => 'Putih',
                'tahun' => 2021,
                'status' => 'maintenance',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('mobil')->insert($mobils);
    }
}
