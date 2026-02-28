@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/laporan.css') }}">
@endpush

@section('content')
<div class="container-fluid p-0">
    
    <div class="d-flex justify-content-end mb-4">
        <form action="{{ route('admin.laporan') }}" method="GET" class="d-flex gap-2 align-items-center">
            <select name="filter" class="form-select border-0 shadow-sm bg-white rounded-pill px-4" onchange="this.form.submit()">
                <option value="hari_ini" {{ $filter == 'hari_ini' ? 'selected' : '' }}>Hari Ini</option>
                <option value="minggu_ini" {{ $filter == 'minggu_ini' ? 'selected' : '' }}>Minggu Ini</option>
                <option value="bulan_ini" {{ $filter == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
            </select>
        </form>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="report-stat-card shadow-sm border-0">
                <span class="label">Total Transaksi</span>
                <span class="value">{{ $totalTransaksi }}</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="report-stat-card shadow-sm border-0">
                <span class="label">Total Pendapatan</span>
                <span class="value text-success-custom">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="report-stat-card shadow-sm border-0">
                <span class="label">Item Terjual</span>
                <span class="value">{{ $itemTerjual }}</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="report-stat-card shadow-sm border-0">
                <span class="label">Rata-rata Transaksi</span>
                <span class="value">Rp {{ number_format($rataRataTransaksi, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-5">
            <div class="data-card shadow-sm border-0">
                <h6 class="data-card-title">Pendapatan per Metode Bayar</h6>
                <div class="mt-4">
                    @forelse($pendapatanMetode as $m)
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <div class="fw-bold text-dark">{{ $m->metode_bayar }}</div>
                                <small class="text-muted">{{ $m->total_transaksi }} transaksi</small>
                            </div>
                            <div class="fw-bold text-dark">Rp {{ number_format($m->total_nominal, 0, ',', '.') }}</div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-5">Belum ada data</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="data-card shadow-sm border-0">
                <h6 class="data-card-title">Menu Terlaris</h6>
                <div class="mt-2">
                    @forelse($menuTerlaris as $index => $item)
                        <div class="best-seller-item">
                            <div class="best-seller-rank">{{ $index + 1 }}</div>
                            <div class="best-seller-info">
                                <p class="best-seller-name">{{ $item->menu->nama_menu ?? 'Menu Terhapus' }}</p>
                                <span class="best-seller-count">{{ $item->total_qty }} terjual</span>
                            </div>
                            <div class="best-seller-price">Rp {{ number_format($item->total_rupiah, 0, ',', '.') }}</div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-5">Belum ada data penjualan</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>
@endsection