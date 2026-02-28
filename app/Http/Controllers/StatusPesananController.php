<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;

class StatusPesananController extends Controller
{
    public function index(Request $request)
    {
        // Ambil status dari filter (default: Semua)
        $statusFilter = $request->get('status', 'Semua');

        $query = Transaksi::with(['meja', 'detailTransaksis.menu']);

        // Jika filter bukan 'Semua', tambahkan kondisi where
        if ($statusFilter != 'Semua') {
            $query->where('status_pesanan', $statusFilter);
        }

        // Urutkan: Diproses di atas, lalu Selesai, kemudian berdasarkan waktu terbaru
        $pesanans = $query->orderByRaw("FIELD(status_pesanan, 'Diproses', 'Selesai') ASC")
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.status-pesanan.index', compact('pesanans', 'statusFilter'));
    }

    public function updateToSelesai($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->status_pesanan = 'Selesai';
        $transaksi->save();

        return redirect()->back()->with('success', 'Pesanan ' . $transaksi->no_invoice . ' telah selesai!');
    }

    public function updateToDiproses($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->status_pesanan = 'Diproses';
        $transaksi->save();

        return redirect()->back()->with('success', 'Pesanan ' . $transaksi->no_invoice . ' dikembalikan ke antrean Diproses.');
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->detailTransaksis()->delete();
        $transaksi->delete();

        return redirect()->back()->with('success', 'Data pesanan berhasil dihapus dari antrean.');
    }
}