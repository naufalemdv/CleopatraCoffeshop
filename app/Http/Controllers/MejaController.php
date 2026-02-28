<?php

namespace App\Http\Controllers;
use App\Models\Meja;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode; // Ini yang tadi error karena belum di-install

class MejaController extends Controller
{
    public function index()
    {
        $mejas = Meja::orderBy('no_meja', 'asc')->get();
        return view('admin.meja.index', compact('mejas'));
    }

    public function store(Request $request)
    {
        $request->validate(['no_meja' => 'required|unique:mejas', 'kapasitas' => 'required|integer']);
        Meja::create($request->all());
        return redirect()->back()->with('success', 'Meja berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['no_meja' => 'required|unique:mejas,no_meja,' . $id, 'kapasitas' => 'required|integer']);
        $meja = Meja::findOrFail($id);
        $meja->update($request->all());
        return redirect()->back()->with('success', 'Data meja berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $meja = Meja::findOrFail($id);
        $meja->delete();
        return redirect()->back()->with('success', 'Meja berhasil dihapus.');
    }

    // FUNGSI Generate & Print QR Code
    public function printQR($id)
    {
        $meja = Meja::findOrFail($id);
        
        // Membuat URL unik untuk discan pelanggan (contoh: http://127.0.0.1:8000/order/VIP1)
        $url = url('/order/' . $meja->no_meja);
        
        // Generate QR Code format SVG
        $qrCode = QrCode::size(250)->margin(2)->generate($url);
        return view('admin.meja.qr', compact('meja', 'qrCode', 'url'));
    }
}