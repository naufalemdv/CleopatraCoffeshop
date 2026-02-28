@extends('layouts.app')

@section('title', 'Monitor Status Pesanan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/status-pesanan.css') }}">
@endpush

@section('content')
<div class="container-fluid p-0">
    
    <div class="filter-wrapper mb-4">
        <div class="d-flex align-items-center gap-3 bg-white p-3 rounded-4 shadow-sm">
            <span class="fw-bold text-dark small"><i class="bi bi-funnel me-1"></i> Filter Status:</span>
            <div class="btn-group-filter d-flex gap-2">
                <a href="{{ route('admin.status-pesanan', ['status' => 'Semua']) }}" 
                   class="filter-pill {{ $statusFilter == 'Semua' ? 'active' : '' }}">Semua</a>
                
                <a href="{{ route('admin.status-pesanan', ['status' => 'Diproses']) }}" 
                   class="filter-pill pill-warning {{ $statusFilter == 'Diproses' ? 'active' : '' }}">
                   <span class="dot"></span> Sedang Diproses
                </a>
                
                <a href="{{ route('admin.status-pesanan', ['status' => 'Selesai']) }}" 
                   class="filter-pill pill-success {{ $statusFilter == 'Selesai' ? 'active' : '' }}">
                   <span class="dot"></span> Sudah Selesai
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4 alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="order-grid">
        @forelse($pesanans as $p)
        <div class="order-card shadow-sm {{ $p->status_pesanan == 'Diproses' ? 'border-processing' : 'border-finished' }}">
            <div class="order-header d-flex justify-content-between align-items-center">
                <div>
                    <span class="fw-bold text-dark d-block">{{ $p->no_invoice }}</span>
                    <small class="text-muted">{{ $p->created_at->format('H:i') }} • Meja {{ $p->meja->no_meja ?? '-' }}</small>
                </div>
                <span class="badge-status {{ $p->status_pesanan == 'Diproses' ? 'bg-processing' : 'bg-finished' }}">
                    {{ $p->status_pesanan }}
                </span>
            </div>
            
            <div class="order-body">
                <div class="mb-3">
                    <small class="text-muted d-block">Nama Pelanggan:</small>
                    <span class="fw-bold text-dark">{{ $p->nama_pelanggan }}</span>
                </div>

                <div class="menu-list">
                    <ul class="list-unstyled mb-0">
                        @foreach($p->detailTransaksis as $item)
                        <li class="d-flex justify-content-between border-bottom border-dashed py-2">
                            <span class="small">{{ $item->qty }}x {{ $item->menu->nama_menu }}</span>
                            @if($item->catatan)
                                <small class="text-accent">({{ $item->catatan }})</small>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                </div>

                <div class="mt-4">
                    <div class="d-flex gap-2">
                        @if($p->status_pesanan == 'Diproses')
                            <form action="{{ route('admin.status-pesanan.update', $p->id) }}" method="POST" class="flex-grow-1">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-custom w-100 fw-bold py-2 shadow-sm">
                                    <i class="bi bi-check2-circle me-1"></i> Selesaikan
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.status-pesanan.diproses', $p->id) }}" method="POST" class="flex-grow-1">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-outline-custom w-100 fw-bold py-2">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i> Proses Kembali
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('admin.status-pesanan.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus antrean pesanan ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-light text-danger border px-3" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <div class="bg-white rounded-4 p-5 shadow-sm border">
                <i class="bi bi-clipboard-x fs-1 text-muted opacity-25"></i>
                <p class="mt-3 text-muted">Tidak ada pesanan dengan status <strong>{{ $statusFilter }}</strong>.</p>
                <a href="{{ route('admin.status-pesanan') }}" class="btn btn-sm btn-outline-secondary mt-2">Tampilkan Semua</a>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection