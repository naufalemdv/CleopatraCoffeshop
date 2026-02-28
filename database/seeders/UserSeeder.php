<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Matikan pengecekan Foreign Key agar bisa truncate tabel yang berelasi
        Schema::disableForeignKeyConstraints();

        // 2. Kosongkan tabel users
        DB::table('users')->truncate();

        // 3. Masukkan data Admin
        User::create([
            'nama' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'), 
            'role' => 'admin',
        ]);

        // 4. Hidupkan kembali pengecekan Foreign Key
        Schema::enableForeignKeyConstraints();
    }
}