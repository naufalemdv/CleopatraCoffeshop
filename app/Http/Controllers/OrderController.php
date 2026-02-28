<?php

namespace App\Http\Controllers;

use App\Models\Meja;
use App\Models\Menu;
use App\Models\Kategori;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception; 
use Barryvdh\DomPDF\Facade\Pdf; 

class OrderController extends Controller
{
    public function index($no_meja, Request $request)
    {
        $meja = Meja::where('no_meja', $no_meja)->firstOrFail();
        $request->session()->put('meja_id', $meja->id);
        $request->session()->put('no_meja', $meja->no_meja);

        $kategoris = Kategori::where('status_aktif', true)->get();
        $menus = Menu::with('kategori')->where('status_aktif', true)->get();

        $cart = $request->session()->get('cart', []);
        $totalCartItems = array_sum(array_column($cart, 'qty'));
        $totalCartPrice = array_sum(array_column($cart, 'subtotal'));

        return view('user.order.index', compact('meja', 'kategoris', 'menus', 'totalCartItems', 'totalCartPrice'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id', 
            'qty' => 'required|integer|min:1'
        ]);

        $menu = Menu::findOrFail($request->menu_id);
        $catatan = $request->catatan ?? '';
        $cart = $request->session()->get('cart', []);
        
        $cartKey = $menu->id . '_' . md5($catatan);

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['qty'] += $request->qty;
            $cart[$cartKey]['subtotal'] = $cart[$cartKey]['qty'] * $menu->harga;
        } else {
            $cart[$cartKey] = [
                'menu_id' => $menu->id, 
                'nama_menu' => $menu->nama_menu, 
                'harga' => $menu->harga,
                'qty' => $request->qty, 
                'catatan' => $catatan, 
                'subtotal' => $menu->harga * $request->qty,
                'foto' => $menu->foto // Simpan nama file foto asli
            ];
        }
        
        $request->session()->put('cart', $cart);
        return redirect()->back()->with('success', $menu->nama_menu . ' ditambahkan ke keranjang!');
    }

    public function cart(Request $request)
    {
        $no_meja = $request->session()->get('no_meja');
        if (!$no_meja) { return redirect('https://google.com'); }
        $cart = $request->session()->get('cart', []);
        $totalPrice = array_sum(array_column($cart, 'subtotal'));
        return view('user.order.cart', compact('cart', 'totalPrice', 'no_meja'));
    }

    public function removeFromCart(Request $request)
    {
        $cart = $request->session()->get('cart', []);
        if (isset($cart[$request->cart_key])) {
            unset($cart[$request->cart_key]);
            $request->session()->put('cart', $cart);
        }
        return redirect()->back();
    }

    public function checkout(Request $request)
    {
        $meja_id = $request->session()->get('meja_id');
        $cart = $request->session()->get('cart', []);

        if (empty($cart) || !$meja_id) { return redirect()->back(); }
        $request->validate(['nama_pelanggan' => 'required|string|max:100']);

        DB::beginTransaction();
        try {
            $noInvoice = 'INV-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(2)));
            $totalBayar = array_sum(array_column($cart, 'subtotal'));

            $transaksi = Transaksi::create([
                'no_invoice' => $noInvoice,
                'nama_pelanggan' => $request->nama_pelanggan,
                'meja_id' => $meja_id,
                'user_id' => 1, 
                'total_bayar' => $totalBayar,
                'jumlah_bayar' => 0,
                'metode_bayar' => 'Tunai', 
                'status_pembayaran' => 'Belum Bayar', 
                'status_pesanan' => 'Diproses', 
            ]);

            foreach ($cart as $item) {
                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'menu_id' => $item['menu_id'],
                    'qty' => $item['qty'],
                    'harga_satuan' => $item['harga'],
                    'subtotal' => $item['subtotal'],
                    'catatan' => $item['catatan'] ?? null,
                ]);
            }

            $meja = Meja::find($meja_id);
            if ($meja) {
                $meja->status_meja = 'Terisi'; 
                $meja->save();
            }

            DB::commit();
            $request->session()->forget('cart');
            $request->session()->put('transaksi_id', $transaksi->id);

            return redirect()->route('order.success');

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal memproses pesanan.');
        }
    }

    public function success(Request $request)
    {
        $transaksi_id = $request->session()->get('transaksi_id');
        if (!$transaksi_id) { return redirect('/'); }
        $transaksi = Transaksi::with(['meja', 'detailTransaksis.menu'])->findOrFail($transaksi_id);
        return view('user.order.success', compact('transaksi'));
    }

    public function downloadPDF($id)
    {
        $transaksi = Transaksi::with(['meja', 'detailTransaksis.menu'])->findOrFail($id);
        $pdf = Pdf::loadView('user.order.invoice-pdf', compact('transaksi'));
        return $pdf->download('Invoice-' . $transaksi->no_invoice . '.pdf');
    }
}