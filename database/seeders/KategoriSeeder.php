<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoris = [
            [
                'nama_kategori' => 'Makanan',
                'deskripsi' => 'Hidangan utama untuk makan siang dan malam',
                'status_aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Minuman',
                'deskripsi' => 'Berbagai jenis minuman segar dan hangat',
                'status_aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Snack & Dessert',
                'deskripsi' => 'Cemilan ringan dan makanan penutup yang manis',
                'status_aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Paket',
                'deskripsi' => 'Paket hemat untuk makan bersama',
                'status_aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        Kategori::insert($kategoris);
    }
}