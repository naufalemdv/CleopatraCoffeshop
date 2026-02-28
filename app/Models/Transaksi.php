<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_invoice',
        'nama_pelanggan',
        'meja_id',
        'user_id',
        'total_bayar',
        'jumlah_bayar',
        'metode_bayar',
        'catatan_transaksi',
        'status_pembayaran',
        'status_pesanan'
    ];

    // Relasi: Setiap Transaksi dimiliki oleh satu Meja
    public function meja()
    {
        return $this->belongsTo(Meja::class);
    }

    // Relasi: Setiap Transaksi diproses oleh satu User (Kasir)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Satu Transaksi memiliki banyak Detail Transaksi (Item yang dipesan)
    public function detailTransaksis()
    {
        return $this->hasMany(DetailTransaksi::class);
    }
}