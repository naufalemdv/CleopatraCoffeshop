<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('no_invoice')->unique();
            $table->string('nama_pelanggan')->nullable(); // Null jika kasir belum input nama
            $table->foreignId('meja_id')->nullable()->constrained('mejas')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Nullable agar pesanan QR User mandiri bisa masuk
            $table->decimal('total_bayar', 15, 2)->default(0);
            $table->decimal('jumlah_bayar', 15, 2)->nullable(); // Uang pas/uang yang diberikan
            $table->enum('metode_bayar', ['Tunai', 'Kartu', 'QRIS', 'Transfer'])->nullable();
            $table->text('catatan_transaksi')->nullable();
            $table->enum('status_pembayaran', ['Belum Bayar', 'Selesai'])->default('Belum Bayar');
            $table->enum('status_pesanan', ['Diproses', 'Selesai'])->default('Diproses');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};