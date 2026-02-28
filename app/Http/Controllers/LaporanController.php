<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Pengaturan Filter Tanggal (Default: Hari Ini)
        $filter = $request->get('filter', 'hari_ini');
        $startDate = Carbon::today();
        $endDate = Carbon::today();

        if ($filter == 'minggu_ini') {
            $startDate = Carbon::now()->startOfWeek();
            $endDate = Carbon::now()->endOfWeek();
        } elseif ($filter == 'bulan_ini') {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
        } elseif ($request->filled('from_date') && $request->filled('to_date')) {
            $startDate = Carbon::parse($request->from_date);
            $endDate = Carbon::parse($request->to_date);
            $filter = 'custom';
        }

        // 1. Statistik Utama (Cards)
        $totalTransaksi = Transaksi::whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])->count();
        $totalPendapatan = Transaksi::whereBetween('created_at', [$startDate, $endDate])->sum('total_bayar');
        $itemTerjual = DetailTransaksi::whereHas('transaksi', function($q) use ($startDate, $endDate) {
            $q->whereBetween('created_at', [$startDate, $endDate]);
        })->sum('qty');
        $rataRataTransaksi = $totalTransaksi > 0 ? $totalPendapatan / $totalTransaksi : 0;

        // 2. Pendapatan per Metode Bayar
        $pendapatanMetode = Transaksi::whereBetween('created_at', [$startDate, $endDate])
            ->select('metode_bayar', DB::raw('count(*) as total_transaksi'), DB::raw('sum(total_bayar) as total_nominal'))
            ->groupBy('metode_bayar')
            ->get();

        // 3. Ranking Menu Terlaris
        $menuTerlaris = DetailTransaksi::whereHas('transaksi', function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->select('menu_id', DB::raw('sum(qty) as total_qty'), DB::raw('sum(subtotal) as total_rupiah'))
            ->with('menu')
            ->groupBy('menu_id')
            ->orderBy('total_qty', 'desc')
            ->take(5) // Ambil Top 5
            ->get();

        return view('admin.laporan.index', compact(
            'totalTransaksi', 'totalPendapatan', 'itemTerjual', 'rataRataTransaksi',
            'pendapatanMetode', 'menuTerlaris', 'filter', 'startDate', 'endDate'
        ));
    }
}