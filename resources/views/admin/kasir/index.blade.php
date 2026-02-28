@extends('layouts.app')

@section('title', 'Kasir')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/kasir.css') }}">
@endpush

@section('content')
<div class="container-fluid p-0">
    <div class="kasir-container">
        
        <div class="menu-section">
            <div class="d-flex gap-2 mb-4 overflow-auto pb-2">
                <button class="btn btn-white shadow-sm rounded-pill px-4 active btn-filter" data-category="all">Semua</button>
                @foreach($kategoris as $kat)
                    <button class="btn btn-white shadow-sm rounded-pill px-4 btn-filter" data-category="{{ $kat->id }}">{{ $kat->nama_kategori }}</button>
                @endforeach
            </div>

            <div class="menu-grid">
                @foreach($menus as $menu)
                <div class="menu-item-card shadow-sm item-card" data-id="{{ $menu->id }}" data-nama="{{ $menu->nama_menu }}" data-harga="{{ (int)$menu->harga }}" data-category="{{ $menu->kategori_id }}">
                    <div class="fw-bold text-dark">{{ $menu->nama_menu }}</div>
                    <div class="small text-muted">{{ $menu->kategori->nama_kategori }}</div>
                    <div class="price">Rp {{ number_format($menu->harga, 0, ',', '.') }}</div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="cart-section shadow-sm">
            <div class="cart-header d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">Pesanan Baru</h6>
                <button class="btn btn-sm text-muted" id="btnClearCart"><i class="bi bi-trash"></i></button>
            </div>
            
            <div class="cart-body" id="cartList">
                <div class="text-center text-muted mt-5 py-5">
                    <i class="bi bi-cart-x fs-1 opacity-25"></i>
                    <p class="small mt-2">Keranjang Kosong</p>
                </div>
            </div>

            <div class="cart-footer">
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Total</span>
                    <span class="fw-bold fs-5" id="displayTotal">Rp 0</span>
                </div>
                <button class="btn btn-custom w-100 py-3 fw-bold rounded-pill shadow" id="btnProsesBayar" disabled>
                    BAYAR SEKARANG
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalBayar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Konfirmasi Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.kasir.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <span class="text-muted d-block small">Total Tagihan</span>
                        <h2 class="fw-bold text-dark" id="modalDisplayTotal">Rp 0</h2>
                    </div>

                    <input type="hidden" name="cart" id="inputCart">
                    <input type="hidden" name="total_keseluruhan" id="inputTotal">

                    <div class="mb-3">
                        <label class="form-label fw-medium small">Pilih Meja</label>
                        <select name="meja_id" class="form-select bg-light border-0" required>
                            <option value="">-- Pilih Meja --</option>
                            @foreach($mejas as $meja)
                                <option value="{{ $meja->id }}">Meja {{ $meja->no_meja }} ({{ $meja->area }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium small">Nama Pelanggan (Opsional)</label>
                        <input type="text" name="nama_pelanggan" class="form-control bg-light border-0" placeholder="Pelanggan Umum">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium small">Metode Pembayaran</label>
                        <div class="d-flex gap-2 method-btn-group">
                            <input type="radio" class="btn-check" name="metode_bayar" id="meth1" value="Tunai" checked>
                            <label class="btn btn-outline-custom flex-fill" for="meth1">Tunai</label>
                            
                            <input type="radio" class="btn-check" name="metode_bayar" id="meth2" value="QRIS">
                            <label class="btn btn-outline-custom flex-fill" for="meth2">QRIS</label>
                            
                            <input type="radio" class="btn-check" name="metode_bayar" id="meth3" value="Kartu">
                            <label class="btn btn-outline-custom flex-fill" for="meth3">Kartu</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium small">Jumlah Bayar (Tunai)</label>
                        <input type="number" name="jumlah_bayar" id="inputNominal" class="form-control form-control-lg fw-bold" required>
                        <div class="mt-2 d-flex gap-2 flex-wrap" id="quickCash">
                            </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium small">Catatan Transaksi</label>
                        <textarea name="catatan_transaksi" class="form-control bg-light border-0" rows="2" placeholder="Catatan tambahan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-custom w-100 py-3 fw-bold rounded-pill">SELESAIKAN PESANAN</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let cart = [];
    let total = 0;

    // 1. Tambah ke Keranjang
    document.querySelectorAll('.item-card').forEach(card => {
        card.addEventListener('click', () => {
            const id = card.dataset.id;
            const nama = card.dataset.nama;
            const harga = parseInt(card.dataset.harga);

            const existing = cart.find(i => i.id === id);
            if (existing) {
                existing.qty++;
            } else {
                cart.push({ id, nama, harga, qty: 1, catatan: '' });
            }
            renderCart();
        });
    });

    function renderCart() {
        const list = document.getElementById('cartList');
        if (cart.length === 0) {
            list.innerHTML = `<div class="text-center text-muted mt-5 py-5"><i class="bi bi-cart-x fs-1 opacity-25"></i><p class="small mt-2">Keranjang Kosong</p></div>`;
            document.getElementById('btnProsesBayar').disabled = true;
            total = 0;
            document.getElementById('displayTotal').innerText = 'Rp 0';
            return;
        }

        list.innerHTML = '';
        total = 0;
        cart.forEach((item, index) => {
            total += item.harga * item.qty;
            list.innerHTML += `
                <div class="cart-item">
                    <i class="bi bi-x-circle-fill btn-remove" onclick="removeItem(${index})"></i>
                    <div class="fw-bold small">${item.nama}</div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <div class="d-flex align-items-center border rounded bg-white">
                            <button class="btn btn-sm py-0" onclick="updateQty(${index}, -1)">-</button>
                            <span class="px-2 small">${item.qty}</span>
                            <button class="btn btn-sm py-0" onclick="updateQty(${index}, 1)">+</button>
                        </div>
                        <span class="small fw-bold">Rp ${ (item.harga * item.qty).toLocaleString('id-ID') }</span>
                    </div>
                    <input type="text" class="form-control form-control-sm mt-2 border-0 bg-white" placeholder="Catatan..." onchange="updateNote(${index}, this.value)" value="${item.catatan}">
                </div>
            `;
        });

        document.getElementById('displayTotal').innerText = 'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('btnProsesBayar').disabled = false;
    }

    function updateQty(index, delta) {
        cart[index].qty += delta;
        if (cart[index].qty < 1) cart.splice(index, 1);
        renderCart();
    }

    function removeItem(index) {
        cart.splice(index, 1);
        renderCart();
    }

    function updateNote(index, val) {
        cart[index].catatan = val;
    }

    // 2. Filter Kategori
    document.querySelectorAll('.btn-filter').forEach(btn => {
        btn.addEventListener('click', () => {
            const cat = btn.dataset.category;
            document.querySelectorAll('.btn-filter').forEach(b => b.classList.remove('active', 'btn-dark'));
            btn.classList.add('active', 'btn-dark');

            document.querySelectorAll('.item-card').forEach(card => {
                if (cat === 'all' || card.dataset.category === cat) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    // 3. Modal Pembayaran
    const modalBayar = new bootstrap.Modal(document.getElementById('modalBayar'));
    document.getElementById('btnProsesBayar').addEventListener('click', () => {
        document.getElementById('modalDisplayTotal').innerText = 'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('inputTotal').value = total;
        document.getElementById('inputCart').value = JSON.stringify(cart);
        document.getElementById('inputNominal').value = total;

        // Tombol Quick Cash
        const quick = document.getElementById('quickCash');
        quick.innerHTML = `<button type="button" class="btn btn-sm btn-outline-secondary btn-quick-cash" onclick="setCash(${total})">Uang Pas</button>`;
        [50000, 100000, 200000].forEach(val => {
            if (val > total) quick.innerHTML += `<button type="button" class="btn btn-sm btn-outline-secondary btn-quick-cash" onclick="setCash(${val})">Rp ${val.toLocaleString('id-ID')}</button>`;
        });

        modalBayar.show();
    });

    function setCash(val) {
        document.getElementById('inputNominal').value = val;
    }

    document.getElementById('btnClearCart').addEventListener('click', () => {
        if(confirm('Kosongkan keranjang?')) {
            cart = [];
            renderCart();
        }
    });
</script>
@endpush