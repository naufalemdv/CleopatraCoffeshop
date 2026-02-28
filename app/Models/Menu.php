<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    // KUNCI UTAMANYA ADA DI SINI
    protected $fillable = [
        'nama_menu',
        'kategori_id',
        'harga',
        'deskripsi_menu',
        'status_aktif',
        'foto', // <--- PASTIKAN KATA INI ADA DI DALAM SINI
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function detailTransaksis()
    {
        return $this->hasMany(DetailTransaksi::class);
    }
}