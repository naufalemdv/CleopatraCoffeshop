<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            [
                'kategori_id' => 1, // Makanan Utama
                'nama_menu' => 'Nasi Goreng Spesial',
                'deskripsi_menu' => 'Nasi goreng dengan telur, ayam, dan sayuran segar',
                'harga' => 25000,
                'status_aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 1, // Makanan Utama
                'nama_menu' => 'Ayam Bakar',
                'deskripsi_menu' => 'Ayam bakar bumbu kecap manis gurih dengan nasi',
                'harga' => 30000,
                'status_aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 2, // Minuman
                'nama_menu' => 'Es Jeruk',
                'deskripsi_menu' => 'Perasan jeruk segar asli dengan es batu',
                'harga' => 8000,
                'status_aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 2, // Minuman
                'nama_menu' => 'Jus Alpukat',
                'deskripsi_menu' => 'Jus alpukat kental dengan siraman susu cokelat',
                'harga' => 12000,
                'status_aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 3, // Snack & Dessert
                'nama_menu' => 'French Fries',
                'deskripsi_menu' => 'Kentang goreng renyah dengan taburan bumbu rahasia',
                'harga' => 15000,
                'status_aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 4, // Paket
                'nama_menu' => 'Ayam Bakar + Es Jeruk',
                'deskripsi_menu' => 'Paket lengkap dengan ayam bakar dan es jeruk',
                'harga' => 50000,
                'status_aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        Menu::insert($menus);
    }
}