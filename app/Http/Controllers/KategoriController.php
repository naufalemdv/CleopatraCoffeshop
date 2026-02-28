<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    // Menampilkan halaman data Kategori
    public function index()
    {
        // Mengambil semua data kategori beserta jumlah menu di dalamnya (withCount)
        $kategoris = Kategori::withCount('menus')->orderBy('id', 'desc')->get();
        return view('admin.kategori.index', compact('kategoris'));
    }

    // Proses menyimpan kategori baru (Create)
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status_aktif' => 'required|boolean',
        ]);

        Kategori::create([
            'nama_kategori' => $request->nama_kategori,
            'deskripsi' => $request->deskripsi,
            'status_aktif' => $request->status_aktif,
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    // Proses memperbarui kategori (Update)
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status_aktif' => 'required|boolean',
        ]);

        $kategori = Kategori::findOrFail($id);
        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
            'deskripsi' => $request->deskripsi,
            'status_aktif' => $request->status_aktif,
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil diperbarui!');
    }

    // Proses menghapus kategori (Delete)
    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();

        return redirect()->back()->with('success', 'Kategori berhasil dihapus!');
    }
}