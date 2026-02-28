<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meja extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_meja', // Sesuaikan dengan database
        'kapasitas_kursi', // Sesuaikan dengan database
        'area',
        'status_meja'
    ];

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }
}