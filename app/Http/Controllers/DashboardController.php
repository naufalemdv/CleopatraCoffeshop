<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Menu;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // 1. Ringkasan Statistik Hari Ini
        $transaksiHariIni = Transaksi::whereDate('created_at', $today)->count();
        $pendapatanHariIni = Transaksi::whereDate('created_at', $today)->sum('total_bayar');
        $itemTerjualHariIni = DetailTransaksi::whereHas('transaksi', function($q) use ($today) {
            $q->whereDate('created_at', $today);
        })->sum('qty');
        $totalMenu = Menu::where('status_aktif', true)->count();
        $menuTersedia = $totalMenu . '/' . Menu::count();

        // 2. Data Grafik Pendapatan Mingguan (7 Hari Terakhir)
        $grafikPendapatan = Transaksi::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_bayar) as total')
            )
            ->where('created_at', '>=', Carbon::now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        // 3. Menu Terlaris Hari Ini (Top 5)
        $menuTerlaris = DetailTransaksi::whereHas('transaksi', function($q) use ($today) {
                $q->whereDate('created_at', $today);
            })
            ->select('menu_id', DB::raw('sum(qty) as total_qty'))
            ->with('menu')
            ->groupBy('menu_id')
            ->orderBy('total_qty', 'desc')
            ->take(5)
            ->get();

        // 4. Transaksi Terbaru (5 Terakhir)
        $transaksiTerbaru = Transaksi::with(['user', 'detailTransaksis'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'transaksiHariIni', 'pendapatanHariIni', 'itemTerjualHariIni', 
            'menuTersedia', 'grafikPendapatan', 'menuTerlaris', 'transaksiTerbaru'
        ));
    }
}