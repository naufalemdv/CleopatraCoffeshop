@extends('layouts.app')

@section('title', 'Status Meja')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/status-meja.css') }}">
@endpush

@section('content')
<div class="container-fluid p-0">
    
    <div class="row g-3 mb-4">
        <div class="col-md">
            <div class="stat-card shadow-sm border-start border-4 border-dark">
                <div class="label text-dark fw-bold">Total Meja</div>
                <div class="value">{{ $stats['total'] }}</div>
            </div>
        </div>
        <div class="col-md">
            <div class="stat-card shadow-sm border-start border-4 border-success">
                <div class="label text-success fw-bold">Tersedia</div>
                <div class="value text-success">{{ $stats['tersedia'] }}</div>
            </div>
        </div>
        <div class="col-md">
            <div class="stat-card shadow-sm border-start border-4 border-danger">
                <div class="label text-danger fw-bold">Terisi</div>
                <div class="value text-danger">{{ $stats['terisi'] }}</div>
            </div>
        </div>
        <div class="col-md">
            <div class="stat-card shadow-sm border-start border-4 border-warning">
                <div class="label text-warning fw-bold">Reserved</div>
                <div class="value text-warning">{{ $stats['reserved'] }}</div>
            </div>
        </div>
        <div class="col-md">
            <div class="stat-card shadow-sm border-start border-4 border-secondary">
                <div class="label text-secondary fw-bold">Maintenance</div>
                <div class="value text-secondary">{{ $stats['maintenance'] }}</div>
            </div>
        </div>
    </div>

    <div class="card card-custom p-3 border-0 shadow-sm mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div style="max-width: 250px;">
                <select class="form-select border-0 bg-light fw-medium" id="statusFilter">
                    <option value="all">Semua Status</option>
                    <option value="card-tersedia">Tersedia</option>
                    <option value="card-terisi">Terisi</option>
                    <option value="card-reserved">Reserved</option>
                    <option value="card-maintenance">Maintenance</option>
                </select>
            </div>
        </div>
    </div>

    <div class="status-grid" id="mejaGrid">
        @foreach($mejas as $meja)
            @php
                $statusClass = '';
                $icon = 'bi-grid-3x3-gap';
                if($meja->status_meja == 'Tersedia') $statusClass = 'card-tersedia';
                elseif($meja->status_meja == 'Terisi') $statusClass = 'card-terisi';
                elseif($meja->status_meja == 'Reserved') $statusClass = 'card-reserved';
                else $statusClass = 'card-maintenance';
            @endphp

            <div class="meja-card shadow-sm {{ $statusClass }}">
                <div class="icon-box shadow-sm">
                    <i class="bi {{ $icon }}"></i>
                </div>
                <div class="meja-id">{{ $meja->no_meja }}</div>
                <div class="status-label">{{ $meja->status_meja }}</div>
                <p class="info-text">{{ $meja->kapasitas_kursi }} kursi</p>
                <p class="info-text">{{ $meja->area }}</p>

                @if($meja->status_me_ja == 'Tersedia')
                    <button class="btn-action-meja" data-bs-toggle="modal" data-bs-target="#modalStatus{{ $meja->id }}">
                        Isi / Reservasi
                    </button>
                @elseif($meja->status_meja == 'Terisi')
                    <form action="{{ route('admin.status-meja.update', $meja->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status_meja" value="Tersedia">
                        <button type="submit" class="btn-action-meja">Kosongkan</button>
                    </form>
                @elseif($meja->status_meja == 'Reserved')
                    <form action="{{ route('admin.status-meja.update', $meja->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status_meja" value="Tersedia">
                        <button type="submit" class="btn-action-meja">Batal Reservasi</button>
                    </form>
                @else
                    <form action="{{ route('admin.status-meja.update', $meja->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status_meja" value="Tersedia">
                        <button type="submit" class="btn-action-meja">Selesai</button>
                    </form>
                @endif
            </div>

            <div class="modal fade" id="modalStatus{{ $meja->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-sm">
                    <div class="modal-content border-0 shadow">
                        <div class="modal-body p-4 text-center">
                            <h5 class="fw-bold mb-3">Update Meja {{ $meja->no_meja }}</h5>
                            <form action="{{ route('admin.status-meja.update', $meja->id) }}" method="POST">
                                @csrf
                                <button type="submit" name="status_meja" value="Terisi" class="btn btn-danger w-100 mb-2 py-2">Set Jadi Terisi</button>
                                <button type="submit" name="status_meja" value="Reserved" class="btn btn-warning w-100 mb-2 py-2">Set Jadi Reserved</button>
                                <button type="submit" name="status_meja" value="Maintenance" class="btn btn-secondary w-100 py-2">Maintenance</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

@push('scripts')
<script>
    // JS untuk filter status meja di grid
    document.getElementById('statusFilter').addEventListener('change', function() {
        let filter = this.value;
        let cards = document.querySelectorAll('.meja-card');
        
        cards.forEach(card => {
            if (filter === 'all' || card.classList.contains(filter)) {
                card.style.display = "block";
            } else {
                card.style.display = "none";
            }
        });
    });
</script>
@endpush