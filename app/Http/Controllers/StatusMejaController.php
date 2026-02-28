<?php

namespace App\Http\Controllers;

use App\Models\Meja;
use Illuminate\Http\Request;

class StatusMejaController extends Controller
{
    // Menampilkan halaman monitoring status meja
    public function index()
    {
        $mejas = Meja::orderBy('no_meja', 'asc')->get();
        
        // Data untuk Statistik Card di bagian atas
        $stats = [
            'total' => $mejas->count(),
            'tersedia' => $mejas->where('status_meja', 'Tersedia')->count(),
            'terisi' => $mejas->where('status_meja', 'Terisi')->count(),
            'reserved' => $mejas->where('status_meja', 'Reserved')->count(),
            'maintenance' => $mejas->where('status_meja', 'Maintenance')->count(),
        ];

        return view('admin.status-meja.index', compact('mejas', 'stats'));
    }

    // Fungsi untuk mengubah status meja secara manual
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_meja' => 'required|in:Tersedia,Terisi,Reserved,Maintenance'
        ]);

        $meja = Meja::findOrFail($id);
        $meja->status_meja = $request->status_meja;
        $meja->save();

        return redirect()->back()->with('success', 'Status Meja ' . $meja->no_meja . ' berhasil diperbarui!');
    }
}