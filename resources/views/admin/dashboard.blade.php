@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('content')
<div class="container-fluid p-0">
    
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-box shadow-sm">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-receipt"></i></div>
                <div class="stat-info">
                    <div class="label">Transaksi Hari Ini</div>
                    <div class="value">{{ $transaksiHariIni }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-box shadow-sm">
                <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="bi bi-wallet2"></i></div>
                <div class="stat-info">
                    <div class="label">Pendapatan Hari Ini</div>
                    <div class="value">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-box shadow-sm">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="bi bi-bag-check"></i></div>
                <div class="stat-info">
                    <div class="label">Item Terjual Hari Ini</div>
                    <div class="value">{{ $itemTerjualHariIni }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-box shadow-sm">
                <div class="stat-icon bg-info bg-opacity-10 text-info"><i class="bi bi-cup-hot"></i></div>
                <div class="stat-info">
                    <div class="label">Menu Tersedia</div>
                    <div class="value">{{ $menuTersedia }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-8">
            <div class="dashboard-card shadow-sm">
                <h6 class="card-title-custom">Pendapatan Mingguan</h6>
                <canvas id="incomeChart" style="max-height: 300px;"></canvas>
            </div>
        </div>

        <div class="col-md-4">
            <div class="dashboard-card shadow-sm">
                <h6 class="card-title-custom">Menu Terlaris Hari Ini</h6>
                <div class="mt-3">
                    @forelse($menuTerlaris as $index => $item)
                    <div class="list-item-custom">
                        <div class="item-rank">{{ $index + 1 }}</div>
                        <div class="flex-grow-1">
                            <div class="fw-bold small">{{ $item->menu->nama_menu ?? 'Menu' }}</div>
                            <small class="text-muted">{{ $item->total_qty }} porsi</small>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-5 small">Belum ada data</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-8">
            <div class="dashboard-card shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="card-title-custom mb-0">Transaksi Terbaru</h6>
                    <a href="{{ route('admin.transaksi') }}" class="small text-decoration-none text-muted">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle" style="font-size: 0.85rem;">
                        <tbody>
                            @foreach($transaksiTerbaru as $t)
                            <tr>
                                <td class="py-3">
                                    <div class="fw-bold">{{ $t->no_invoice }}</div>
                                    <small class="text-muted">{{ $t->created_at->format('d/m/Y H:i') }} • {{ $t->user->nama ?? 'admin' }}</small>
                                </td>
                                <td class="text-end py-3">
                                    <div class="fw-bold">Rp {{ number_format($t->total_bayar, 0, ',', '.') }}</div>
                                    <small class="text-muted">{{ $t->detailTransaksis->count() }} item</small>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="d-flex flex-column gap-3">
                <a href="{{ route('admin.kasir') }}" class="quick-action-btn shadow-sm">
                    <i class="bi bi-cart-plus text-success"></i> Mulai Kasir
                </a>
                <a href="{{ route('menu.index') }}" class="quick-action-btn shadow-sm">
                    <i class="bi bi-box-seam text-primary"></i> Kelola Menu
                </a>
                <a href="{{ route('admin.laporan') }}" class="quick-action-btn shadow-sm">
                    <i class="bi bi-bar-chart-line text-warning"></i> Lihat Laporan
                </a>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('incomeChart');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($grafikPendapatan->pluck('date')) !!},
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: {!! json_encode($grafikPendapatan->pluck('total')) !!},
                borderColor: '#FE6807',
                backgroundColor: 'rgba(254, 104, 7, 0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 5
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { font: { family: 'Poppins' } } },
                x: { ticks: { font: { family: 'Poppins' } } }
            }
        }
    });
</script>
@endpush