@extends('layouts.user')

@section('title', 'Katalog Menu')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/user-order.css') }}">
@endpush

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-mobile border-0 text-center py-2" role="alert">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
        </div>
    @endif

    <div class="header-mobile d-flex justify-content-between align-items-center">
        <div>
            <h5 class="fw-bold mb-0 text-dark">Cleopatra Coffee</h5>
            <small class="text-muted" style="font-size: 0.75rem;">Silakan pilih pesanan Anda</small>
        </div>
        <div class="table-badge">
            <i class="bi bi-geo-alt-fill me-1"></i> {{ $meja->no_meja }}
        </div>
    </div>

    <div class="category-wrapper">
        <div class="category-scroll">
            <a href="#" class="category-pill active" onclick="filterMenu('all', this); return false;">Semua Menu</a>
            @foreach($kategoris as $k)
                <a href="#" class="category-pill" onclick="filterMenu('{{ $k->id }}', this); return false;">{{ $k->nama_kategori }}</a>
            @endforeach
        </div>
    </div>

    <div class="menu-list">
        @forelse($menus as $menu)
            <div class="menu-card" id="menu-{{ $menu->id }}" data-kategori="{{ $menu->kategori_id }}">
                
                @if($menu->foto)
                    <img src="{{ asset('storage/' . $menu->foto) }}" alt="{{ $menu->nama_menu }}" class="menu-img">
                @else
                    <div class="menu-img d-flex align-items-center justify-content-center bg-light">
                        <i class="bi bi-image text-muted fs-3"></i>
                    </div>
                @endif

                <div class="menu-info">
                    <div>
                        <div class="menu-title">{{ $menu->nama_menu }}</div>
                        <div class="menu-desc">{{ $menu->deskripsi ?? 'Pilihan terbaik untuk Anda hari ini.' }}</div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <div class="menu-price">Rp {{ number_format($menu->harga, 0, ',', '.') }}</div>
                        
                        <button class="btn-add" data-bs-toggle="modal" data-bs-target="#modalAdd{{ $menu->id }}">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="modal fade modal-bottom" id="modalAdd{{ $menu->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content shadow-lg">
                        <div class="modal-header d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0">{{ $menu->nama_menu }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('order.cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                                
                                <div class="mb-4">
                                    <label class="form-label text-muted small fw-bold">Catatan Khusus (Opsional)</label>
                                    <textarea name="catatan" class="form-control bg-light border-0" rows="2" placeholder="Contoh: Pedas, es sedikit, tidak pakai bawang..."></textarea>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <span class="fw-bold fs-5 text-dark" id="displayPrice{{ $menu->id }}">Rp {{ number_format($menu->harga, 0, ',', '.') }}</span>
                                    
                                    <div class="qty-control">
                                        <button type="button" class="qty-btn" onclick="updateQty({{ $menu->id }}, -1, {{ $menu->harga }})"><i class="bi bi-dash"></i></button>
                                        <input type="number" name="qty" id="qty{{ $menu->id }}" class="qty-input" value="1" min="1" readonly>
                                        <button type="button" class="qty-btn" onclick="updateQty({{ $menu->id }}, 1, {{ $menu->harga }})"><i class="bi bi-plus"></i></button>
                                    </div>
                                </div>

                                <button type="submit" class="btn-submit-cart shadow-sm">
                                    Tambahkan ke Pesanan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
        @empty
            <div class="text-center py-5">
                <i class="bi bi-emoji-frown fs-1 text-muted opacity-50"></i>
                <p class="text-muted mt-3 small">Maaf, saat ini menu sedang tidak tersedia.</p>
            </div>
        @endforelse
    </div>

    @if($totalCartItems > 0)
    <div class="floating-cart">
        <a href="{{ route('order.cart') }}" class="cart-btn">
            <div class="d-flex align-items-center">
                <div class="bg-white text-dark rounded-circle d-flex justify-content-center align-items-center me-3" style="width: 30px; height: 30px; font-size: 0.9rem;">
                    {{ $totalCartItems }}
                </div>
                <span>Keranjang Saya</span>
            </div>
            <span>Rp {{ number_format($totalCartPrice, 0, ',', '.') }} <i class="bi bi-chevron-right ms-2"></i></span>
        </a>
    </div>
    @endif

@endsection

@push('scripts')
<script>
    setTimeout(function() {
        let alertNode = document.querySelector('.alert-mobile');
        if (alertNode) {
            alertNode.style.display = 'none';
        }
    }, 3000);

    function updateQty(menuId, change, hargaSatuan) {
        let qtyInput = document.getElementById('qty' + menuId);
        let currentQty = parseInt(qtyInput.value);
        let newQty = currentQty + change;
        
        if (newQty >= 1) {
            qtyInput.value = newQty;
            let newPrice = newQty * hargaSatuan;
            document.getElementById('displayPrice' + menuId).innerText = 'Rp ' + newPrice.toLocaleString('id-ID');
        }
    }

    function filterMenu(kategoriId, element) {
        let pills = document.querySelectorAll('.category-pill');
        pills.forEach(pill => pill.classList.remove('active'));
        element.classList.add('active');

        let cards = document.querySelectorAll('.menu-card');
        cards.forEach(card => {
            if (kategoriId === 'all') {
                card.style.display = 'flex'; 
            } else {
                if (card.getAttribute('data-kategori') == kategoriId) {
                    card.style.display = 'flex'; 
                } else {
                    card.style.display = 'none'; 
                }
            }
        });
    }
</script>
@endpush