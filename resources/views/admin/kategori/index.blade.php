@extends('layouts.app')

@section('title', 'Kategori Menu')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/kategori.css') }}">
@endpush

@section('header_action')
    <button type="button" class="btn btn-custom rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-lg"></i> Tambah Kategori
    </button>
@endsection

@section('content')
<div class="container-fluid p-0">
    
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card card-custom p-4">
        <div class="position-relative search-container mb-4">
            <i class="bi bi-search search-icon"></i>
            <input type="text" class="form-control search-input" id="searchInput" placeholder="Cari kategori...">
        </div>

        <div class="table-responsive">
            <table class="table table-custom table-hover" id="kategoriTable">
                <thead>
                    <tr>
                        <th width="20%">Nama</th>
                        <th width="35%">Deskripsi</th>
                        <th width="15%" class="text-center">Jumlah Menu</th>
                        <th width="15%" class="text-center">Status</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kategoris as $kategori)
                    <tr>
                        <td class="fw-medium">{{ $kategori->nama_kategori }}</td>
                        <td class="text-muted">{{ $kategori->deskripsi ?? '-' }}</td>
                        <td class="text-center">{{ $kategori->menus_count }} menu</td>
                        <td class="text-center">
                            @if($kategori->status_aktif)
                                <span class="badge-status status-aktif">Aktif</span>
                            @else
                                <span class="badge-status status-nonaktif">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <button class="btn-action" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $kategori->id }}" title="Edit">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            <button class="btn-action" data-bs-toggle="modal" data-bs-target="#modalHapus{{ $kategori->id }}" title="Hapus">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </td>
                    </tr>

                    <div class="modal fade" id="modalEdit{{ $kategori->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow">
                                <div class="modal-header border-bottom-0 pb-0">
                                    <h5 class="modal-title fw-bold text-dark">Edit Kategori</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('kategori.update', $kategori->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label text-muted fw-medium">Nama Kategori</label>
                                            <input type="text" name="nama_kategori" class="form-control" value="{{ $kategori->nama_kategori }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-muted fw-medium">Deskripsi</label>
                                            <textarea name="deskripsi" class="form-control" rows="3">{{ $kategori->deskripsi }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-muted fw-medium">Status</label>
                                            <select name="status_aktif" class="form-select">
                                                <option value="1" {{ $kategori->status_aktif == 1 ? 'selected' : '' }}>Aktif</option>
                                                <option value="0" {{ $kategori->status_aktif == 0 ? 'selected' : '' }}>Nonaktif</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-top-0 pt-0">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-custom">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="modalHapus{{ $kategori->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-sm">
                            <div class="modal-content border-0 shadow">
                                <div class="modal-body text-center p-4">
                                    <i class="bi bi-exclamation-circle text-danger" style="font-size: 3rem;"></i>
                                    <h5 class="mt-3 fw-bold text-dark">Hapus Kategori?</h5>
                                    <p class="text-muted mb-4">Apakah Anda yakin ingin menghapus kategori <b>{{ $kategori->nama_kategori }}</b>? Semua menu di dalamnya mungkin akan terpengaruh.</p>
                                    <form action="{{ route('kategori.destroy', $kategori->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-light w-100 mb-2" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-danger w-100">Ya, Hapus!</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Belum ada data kategori.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-dark">Tambah Kategori Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('kategori.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-muted fw-medium">Nama Kategori</label>
                        <input type="text" name="nama_kategori" class="form-control" placeholder="Cth: Minuman Dingin" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted fw-medium">Deskripsi <small>(Opsional)</small></label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Deskripsi singkat..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted fw-medium">Status</label>
                        <select name="status_aktif" class="form-select">
                            <option value="1" selected>Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-custom">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // JS Sederhana untuk fitur pencarian tabel langsung (tanpa reload)
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('#kategoriTable tbody tr');

        rows.forEach(row => {
            // Hanya periksa baris yang memiliki data (bukan yang 'Belum ada data')
            if (row.cells.length > 1) {
                let nama = row.cells[0].textContent.toLowerCase();
                let deskripsi = row.cells[1].textContent.toLowerCase();
                if (nama.indexOf(filter) > -1 || deskripsi.indexOf(filter) > -1) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            }
        });
    });
</script>
@endpush