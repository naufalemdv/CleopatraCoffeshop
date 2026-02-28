<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::all();
        $menus = Menu::with('kategori')->orderBy('created_at', 'desc')->get();
        return view('admin.menu.index', compact('menus', 'kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'harga' => 'required|integer|min:0',
            'deskripsi_menu' => 'nullable|string',
            // UBAH MAKSIMAL JADI 10MB (10240 KB)
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240', 
        ]);

        $data = $request->all();
        $data['status_aktif'] = $request->has('status_aktif') ? true : false;

        // Proses Upload Foto Baru
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('menu_fotos', 'public');
        }

        Menu::create($data);

        return redirect()->back()->with('success', 'Menu berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'harga' => 'required|integer|min:0',
            'deskripsi_menu' => 'nullable|string',
            // UBAH MAKSIMAL JADI 10MB (10240 KB)
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        $data = $request->all();
        $data['status_aktif'] = $request->has('status_aktif') ? true : false;

        // Proses Ganti Foto
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($menu->foto) {
                Storage::disk('public')->delete($menu->foto);
            }
            // Upload foto baru
            $data['foto'] = $request->file('foto')->store('menu_fotos', 'public');
        }

        $menu->update($data);

        return redirect()->back()->with('success', 'Menu berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        
        // Hapus file foto dari storage sebelum data dihapus dari database
        if ($menu->foto) {
            Storage::disk('public')->delete($menu->foto);
        }
        
        $menu->delete();

        return redirect()->back()->with('success', 'Menu berhasil dihapus!');
    }
}