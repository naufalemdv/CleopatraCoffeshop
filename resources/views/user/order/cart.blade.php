@extends('layouts.user')

@section('title', 'Keranjang Pesanan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/user-checkout.css') }}">
@endpush

@section('content')

    <div class="checkout-header">
        <a href="{{ route('order.index', $no_meja) }}"><i class="bi bi-arrow-left"></i></a>
        <h5 class="fw-bold mb-0">Rincian Pesanan</h5>
    </div>

    @if(empty($cart))
        <div class="text-center py-5 mt-5">
            <i class="bi bi-cart-x fs-1 text-muted opacity-50"></i>
            <p class="text-muted mt-3">Keranjang Anda masih kosong.</p>
            <a href="{{ route('order.index', $no_meja) }}" class="btn btn-outline-custom mt-2 rounded-pill px-4">Kembali ke Menu</a>
        </div>
    @else
        <div class="section-title">Daftar Pesanan (Meja {{ $no_meja }})</div>
        
        <div class="cart-items-container">
            @foreach($cart as $key => $item)
                <div class="cart-item">
                    <div class="item-info">
                        <h6>{{ $item['qty'] }}x {{ $item['nama_menu'] }}</h6>
                        <small class="text-muted">Rp {{ number_format($item['harga'], 0, ',', '.') }}</small>
                        @if($item['catatan'])
                            <br><span class="item-note">Catatan: {{ $item['catatan'] }}</span>
                        @endif
                    </div>
                    <div class="text-end">
                        <span class="fw-bold d-block mb-2">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                        <form action="{{ route('order.cart.remove') }}" method="POST">
                            @csrf
                            <input type="hidden" name="cart_key" value="{{ $key }}">
                            <button type="submit" class="btn btn-sm btn-light text-danger rounded-circle" style="width: 30px; height: 30px; padding: 0;"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="section-title mt-4">Informasi Pelanggan</div>
        <div class="form-customer mb-5 pb-5">
            <form action="{{ route('order.checkout') }}" method="POST" id="checkoutForm">
                @csrf
                <label class="form-label text-dark fw-bold small">Nama Pemesan</label>
                <input type="text" name="nama_pelanggan" class="form-control mb-2" placeholder="Masukkan nama Anda..." required>
                <small class="text-muted" style="font-size: 0.7rem;">Nama ini akan dipanggil saat pesanan diantar.</small>
            </form>
        </div>

        <div class="bottom-checkout">
            <div class="d-flex justify-content-between align-items-center mb-2 px-1">
                <span class="text-muted small fw-bold">Total Pembayaran</span>
                <span class="fs-5 fw-bold text-dark">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
            </div>
            <button type="button" class="btn-process" onclick="document.getElementById('checkoutForm').submit();">
                <span>KIRIM PESANAN</span>
                <i class="bi bi-send-fill"></i>
            </button>
        </div>
    @endif

@endsection