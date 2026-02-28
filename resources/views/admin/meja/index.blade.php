@extends('layouts.app')

@section('title', 'Manajemen Meja')

@section('header_action')
    <button class="btn btn-custom px-4 fw-medium shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahMeja">
        <i class="bi bi-plus-lg me-2"></i>Tambah Meja
    </button>
@endsection

@section('content')
<div class="container-fluid p-0">
    
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm p-4 rounded-4">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr class="text-muted small">
                        <th width="5%">NO</th>
                        <th>NOMOR MEJA</th>
                        <th>KAPASITAS</th>
                        <th class="text-center">STATUS SAAT INI</th>
                        <th class="text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mejas as $index => $m)
                    <tr>
                        <td class="text-muted">{{ $index + 1 }}</td>
                        <td class="fw-bold fs-6">{{ $m->no_meja }}</td>
                        <td>{{ $m->kapasitas }} Orang</td>
                        <td class="text-center">
                            @if($m->status_meja == 'Tersedia')
                                <span class="badge bg-success-light text-success rounded-pill px-3 py-2">Tersedia</span>
                            @else
                                <span class="badge bg-danger-light text-danger rounded-pill px-3 py-2">Terisi</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('meja.qr', $m->id) }}" target="_blank" class="btn btn-sm btn-dark px-3 rounded-3" title="Cetak QR Code Standee">
                                    <i class="bi bi-qr-code-scan"></i> Cetak QR
                                </a>

                                <button class="btn btn-sm btn-light border rounded-3" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $m->id }}" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                
                                <form action="{{ route('meja.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Hapus meja ini permanen?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light text-danger border rounded-3" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <div class="modal fade" id="modalEdit{{ $m->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow">
                                <form action="{{ route('meja.update', $m->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-header border-bottom-0 pb-0">
                                        <h5 class="modal-title fw-bold">Edit Meja</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <div class="mb-3">
                                            <label class="form-label text-muted small fw-bold">Nomor/Nama Meja</label>
                                            <input type="text" name="no_meja" class="form-control form-control-lg bg-light border-0" value="{{ $m->no_meja }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-muted small fw-bold">Kapasitas Kursi</label>
                                            <input type="number" name="kapasitas" class="form-control form-control-lg bg-light border-0" value="{{ $m->kapasitas }}" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-top-0 pt-0 px-4 pb-4">
                                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-custom px-4">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">Belum ada meja yang terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahMeja" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('meja.store') }}" method="POST">
                @csrf
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Tambah Meja Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Nomor/Nama Meja</label>
                        <input type="text" name="no_meja" class="form-control form-control-lg bg-light border-0" placeholder="Contoh: T01 atau VIP-1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Kapasitas Kursi</label>
                        <input type="number" name="kapasitas" class="form-control form-control-lg bg-light border-0" placeholder="Contoh: 4" required>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0 px-4 pb-4">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-custom px-4">Simpan Meja</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection