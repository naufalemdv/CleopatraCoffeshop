<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaksi_id',
        'menu_id',
        'qty',
        'harga_satuan',
        'subtotal',
        'catatan'
    ];

    // Relasi: Setiap Detail Transaksi adalah bagian dari satu Transaksi
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    // Relasi: Setiap Detail Transaksi merujuk pada satu Menu
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}