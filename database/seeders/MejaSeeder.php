<?php

namespace Database\Seeders;

use App\Models\Meja;
use Illuminate\Database\Seeder;

class MejaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mejas = [
            ['no_meja' => '001', 'kapasitas_kursi' => 4, 'area' => 'Teras', 'status_meja' => 'Terisi'],
            ['no_meja' => '002', 'kapasitas_kursi' => 4, 'area' => 'Teras', 'status_meja' => 'Terisi'],
            ['no_meja' => '003', 'kapasitas_kursi' => 2, 'area' => 'Teras', 'status_meja' => 'Tersedia'],
            ['no_meja' => 'T01', 'kapasitas_kursi' => 2, 'area' => 'Lantai 1', 'status_meja' => 'Reserved'],
            ['no_meja' => 'T02', 'kapasitas_kursi' => 4, 'area' => 'Lantai 1', 'status_meja' => 'Tersedia'],
            ['no_meja' => 'T03', 'kapasitas_kursi' => 4, 'area' => 'Lantai 1', 'status_meja' => 'Tersedia'],
            ['no_meja' => 'T04', 'kapasitas_kursi' => 4, 'area' => 'Lantai 1', 'status_meja' => 'Tersedia'],
            ['no_meja' => 'T05', 'kapasitas_kursi' => 4, 'area' => 'Lantai 1', 'status_meja' => 'Tersedia'],
            ['no_meja' => 'T06', 'kapasitas_kursi' => 4, 'area' => 'Lantai 1', 'status_meja' => 'Tersedia'],
            ['no_meja' => 'T11', 'kapasitas_kursi' => 2, 'area' => 'Lantai 2', 'status_meja' => 'Reserved'],
            ['no_meja' => 'T12', 'kapasitas_kursi' => 4, 'area' => 'Lantai 2', 'status_meja' => 'Tersedia'],
            ['no_meja' => 'T13', 'kapasitas_kursi' => 4, 'area' => 'Lantai 2', 'status_meja' => 'Tersedia'],
            ['no_meja' => 'T14', 'kapasitas_kursi' => 4, 'area' => 'Lantai 2', 'status_meja' => 'Tersedia'],
            ['no_meja' => 'T15', 'kapasitas_kursi' => 4, 'area' => 'Lantai 2', 'status_meja' => 'Tersedia'],
            ['no_meja' => 'T16', 'kapasitas_kursi' => 4, 'area' => 'Lantai 2', 'status_meja' => 'Tersedia'],
            ['no_meja' => 'VIP1', 'kapasitas_kursi' => 6, 'area' => 'Ruang VIP', 'status_meja' => 'Tersedia'],
            ['no_meja' => 'VIP2', 'kapasitas_kursi' => 6, 'area' => 'Ruang VIP', 'status_meja' => 'Tersedia'],
        ];

        // Looping untuk menambahkan created_at dan updated_at
        foreach ($mejas as &$meja) {
            $meja['created_at'] = now();
            $meja['updated_at'] = now();
        }

        Meja::insert($mejas);
    }
}