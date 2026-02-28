<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
        'status_aktif'
    ];

    // Relasi: Satu Kategori memiliki banyak Menu
    public function menus()
    {
        return $this->hasMany(Menu::class);
    }
}