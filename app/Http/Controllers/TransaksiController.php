<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        // Pengaturan rentang tanggal default (Hari ini: 24/02/2026)
        $fromDate = $request->get('from_date', date('Y-m-d'));
        $toDate = $request->get('to_date', date('Y-m-d'));

        $query = Transaksi::with(['user', 'meja']);

        if ($request->filled('search')) {
            $query->where('no_invoice', 'like', '%' . $request->search . '%');
        }

        $transaksis = $query->whereDate('created_at', '>=', $fromDate)
                            ->whereDate('created_at', '<=', $toDate)
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('admin.transaksi.index', compact('transaksis', 'fromDate', 'toDate'));
    }

    // FUNGSI UNTUK MENGHAPUS TRANSAKSI (DIPERTAHANKAN)
    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        // Hapus detail terlebih dahulu karena relasi Foreign Key
        $transaksi->detailTransaksis()->delete();
        $transaksi->delete();

        return redirect()->back()->with('success', 'Data transaksi berhasil dihapus dari riwayat.');
    }

    // FUNGSI PRINT (SOLUSI ERROR ANDA)
    public function print($id)
    {
        // Mengambil data transaksi lengkap dengan detail menu dan nomor meja
        $transaksi = Transaksi::with(['meja', 'detailTransaksis.menu', 'user'])->findOrFail($id);
        
        return view('admin.transaksi.print', compact('transaksi'));
    }

    // FUNGSI BARU: MENGUBAH STATUS PEMBAYARAN JADI SELESAI
    public function markAsPaid($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->status_pembayaran = 'Selesai'; // Mengubah status menjadi Selesai
        $transaksi->save();

        return redirect()->back()->with('success', 'Pembayaran untuk Invoice ' . $transaksi->no_invoice . ' berhasil diselesaikan!');
    }
}