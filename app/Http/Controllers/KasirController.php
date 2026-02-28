<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Meja;
use App\Models\Kategori;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::where('status_aktif', true)->get();
        $menus = Menu::where('status_aktif', true)->with('kategori')->get();
        $mejas = Meja::where('status_meja', 'Tersedia')->orderBy('no_meja', 'asc')->get();

        return view('admin.kasir.index', compact('kategoris', 'menus', 'mejas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'meja_id' => 'required|exists:mejas,id',
            'metode_bayar' => 'required|in:Tunai,Kartu,QRIS,Transfer',
            'jumlah_bayar' => 'required|numeric',
            'cart' => 'required|json',
        ]);

        $cartData = json_decode($request->cart, true);

        DB::beginTransaction();
        try {
            $noInvoice = 'INV-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(2)));

            // SET STATUS PESANAN KE 'Diproses'
            $transaksi = Transaksi::create([
                'no_invoice' => $noInvoice,
                'nama_pelanggan' => $request->nama_pelanggan ?? 'Pelanggan Umum',
                'meja_id' => $request->meja_id,
                'user_id' => 1, 
                'total_bayar' => $request->total_keseluruhan,
                'jumlah_bayar' => $request->jumlah_bayar,
                'metode_bayar' => $request->metode_bayar,
                'catatan_transaksi' => $request->catatan_transaksi,
                'status_pembayaran' => 'Selesai',
                'status_pesanan' => 'Diproses', // Pesanan masuk dapur dulu
            ]);

            foreach ($cartData as $item) {
                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'menu_id' => $item['id'],
                    'qty' => $item['qty'],
                    'harga_satuan' => $item['harga'],
                    'subtotal' => $item['qty'] * $item['harga'],
                    'catatan' => $item['catatan'] ?? null,
                ]);
            }

            // Update status meja menjadi Terisi
            $meja = Meja::find($request->meja_id);
            $meja->status_meja = 'Terisi';
            $meja->save();

            DB::commit();
            return redirect()->route('admin.kasir')->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
}