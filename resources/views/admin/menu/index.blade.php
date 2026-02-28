@extends('layouts.app')

@section('title', 'Daftar Menu')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
@endpush

@section('header_action')
    <button type="button" class="btn btn-custom rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-lg"></i> Tambah Menu
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
        
        <div class="filter-container">
            <div class="search-wrapper">
                <i class="bi bi-search search-icon"></i>
                <input type="text" class="form-control search-input" id="searchInput" placeholder="Cari menu...">
            </div>
            
            <select class="form-select filter-select" id="filterKategori">
                <option value="all">Semua Kategori</option>
                @foreach($kategoris as $kat)
                    <option value="{{ $kat->nama_kategori }}">{{ $kat->nama_kategori }}</option>
                @endforeach
            </select>
        </div>

        <div class="table-responsive">
            <table class="table table-custom table-hover" id="menuTable">
                <thead>
                    <tr>
                        <th width="35%">Menu</th>
                        <th width="20%">Kategori</th>
                        <th width="20%">Harga</th>
                        <th width="10%" class="text-center">Status</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($menus as $menu)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($menu->foto)
                                    <img src="{{ asset('storage/' . $menu->foto) }}" alt="Foto" class="rounded me-3 shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center text-muted shadow-sm" style="width: 50px; height: 50px;">
                                        <i class="bi bi-image"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="menu-title">{{ $menu->nama_menu }}</div>
                                    <div class="menu-desc" title="{{ $menu->deskripsi_menu }}">{{ $menu->deskripsi_menu ?? 'Tidak ada deskripsi' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="kategori-badge">{{ $menu->kategori->nama_kategori ?? 'Tanpa Kategori' }}</span>
                        </td>
                        <td class="fw-medium text-dark">
                            Rp {{ number_format($menu->harga, 0, ',', '.') }}
                        </td>
                        <td class="text-center">
                            <div class="form-check form-switch d-flex justify-content-center">
                                <input class="form-check-input" type="checkbox" disabled {{ $menu->status_aktif ? 'checked' : '' }}>
                            </div>
                        </td>
                        <td class="text-center">
                            <button class="btn-action" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $menu->id }}" title="Edit">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            <button class="btn-action" data-bs-toggle="modal" data-bs-target="#modalHapus{{ $menu->id }}" title="Hapus">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </td>
                    </tr>

                    <div class="modal fade" id="modalEdit{{ $menu->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow">
                                <div class="modal-header border-bottom-0 pb-0">
                                    <h5 class="modal-title fw-bold text-dark">Edit Menu</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('menu.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        
                                        <div class="mb-3">
                                            <label class="form-label text-muted fw-medium">Foto Menu <small>(Opsional)</small></label>
                                            @if($menu->foto)
                                                <div class="mb-2">
                                                    <img src="{{ asset('storage/' . $menu->foto) }}" alt="Foto Lama" class="rounded border" style="height: 80px; object-fit: cover;">
                                                </div>
                                            @endif
                                            <input type="file" name="foto" class="form-control" accept="image/jpeg,image/png,image/jpg,image/webp">
                                            <small class="text-muted" style="font-size: 0.75rem;">Biarkan kosong jika tidak ingin mengganti foto. (Maks 2MB)</small>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label text-muted fw-medium">Nama Menu</label>
                                            <input type="text" name="nama_menu" class="form-control" value="{{ $menu->nama_menu }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-muted fw-medium">Kategori</label>
                                            <select name="kategori_id" class="form-select" required>
                                                <option value="" disabled>Pilih Kategori</option>
                                                @foreach($kategoris as $kat)
                                                    <option value="{{ $kat->id }}" {{ $menu->kategori_id == $kat->id ? 'selected' : '' }}>
                                                        {{ $kat->nama_kategori }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-muted fw-medium">Harga (Rp)</label>
                                            <input type="number" name="harga" class="form-control" value="{{ intval($menu->harga) }}" min="0" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-muted fw-medium">Deskripsi <small>(Opsional)</small></label>
                                            <textarea name="deskripsi_menu" class="form-control" rows="2">{{ $menu->deskripsi_menu }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-muted fw-medium">Status Aktif</label>
                                            <select name="status_aktif" class="form-select">
                                                <option value="1" {{ $menu->status_aktif == 1 ? 'selected' : '' }}>Aktif (Tersedia)</option>
                                                <option value="0" {{ $menu->status_aktif == 0 ? 'selected' : '' }}>Nonaktif (Habis)</option>
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

                    <div class="modal fade" id="modalHapus{{ $menu->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-sm">
                            <div class="modal-content border-0 shadow">
                                <div class="modal-body text-center p-4">
                                    <i class="bi bi-exclamation-circle text-danger" style="font-size: 3rem;"></i>
                                    <h5 class="mt-3 fw-bold text-dark">Hapus Menu?</h5>
                                    <p class="text-muted mb-4">Apakah Anda yakin ingin menghapus <b>{{ $menu->nama_menu }}</b>?</p>
                                    <form action="{{ route('menu.destroy', $menu->id) }}" method="POST">
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
                        <td colspan="5" class="text-center py-4 text-muted">Belum ada data menu.</td>
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
                <h5 class="modal-title fw-bold text-dark">Tambah Menu Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('menu.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    
                    <div class="mb-3">
                        <label class="form-label text-muted fw-medium">Foto Menu <small>(Opsional)</small></label>
                        <input type="file" name="foto" class="form-control" accept="image/jpeg,image/png,image/jpg,image/webp">
                        <small class="text-muted" style="font-size: 0.75rem;">Format gambar: JPG, PNG, WEBP. Maksimal 2MB.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted fw-medium">Nama Menu</label>
                        <input type="text" name="nama_menu" class="form-control" placeholder="Cth: Kopi Susu Aren" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted fw-medium">Kategori</label>
                        <select name="kategori_id" class="form-select" required>
                            <option value="" selected disabled>-- Pilih Kategori --</option>
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted fw-medium">Harga (Rp)</label>
                        <input type="number" name="harga" class="form-control" placeholder="Cth: 25000" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted fw-medium">Deskripsi <small>(Opsional)</small></label>
                        <textarea name="deskripsi_menu" class="form-control" rows="2" placeholder="Penjelasan singkat menu..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted fw-medium">Status Aktif</label>
                        <select name="status_aktif" class="form-select">
                            <option value="1" selected>Aktif (Tersedia)</option>
                            <option value="0">Nonaktif (Habis)</option>
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
    // JS Sederhana untuk fitur pencarian & Filter Kategori
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const filterKategori = document.getElementById('filterKategori');
        const tableRows = document.querySelectorAll('#menuTable tbody tr');

        function filterTable() {
            let searchTerm = searchInput.value.toLowerCase();
            let selectedKat = filterKategori.value;

            tableRows.forEach(row => {
                if (row.cells.length > 1) { // Abaikan baris "Belum ada data"
                    let menuName = row.cells[0].querySelector('.menu-title').textContent.toLowerCase();
                    let menuKat = row.cells[1].textContent.trim();
                    
                    let matchSearch = menuName.indexOf(searchTerm) > -1;
                    let matchKategori = (selectedKat === 'all' || menuKat === selectedKat);

                    if (matchSearch && matchKategori) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                }
            });
        }

        searchInput.addEventListener('keyup', filterTable);
        filterKategori.addEventListener('change', filterTable);
    });
</script>
@endpush