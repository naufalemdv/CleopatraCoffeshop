@extends('layouts.user')

@section('title', 'Status Pesanan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/user-tracking.css') }}">
@endpush

@section('content')

    <div class="tracking-header">
        <h5 class="fw-bold mb-0 text-dark">Pelacakan Pesanan</h5>
    </div>

    <div class="status-card">
        @if($transaksi->status_pesanan == 'Diproses')
            <div style="position: relative;">
                <div class="pulse-ring"></div>
                <div class="status-icon diproses">
                    <i class="bi bi-fire" style="animation: spinSlow 3s linear infinite;"></i>
                </div>
            </div>
            <h4 class="fw-bold text-dark">Sedang Dimasak!</h4>
            <p class="text-muted small mb-0 px-2">Dapur kami sedang menyiapkan pesanan Anda dengan penuh cinta. Mohon tunggu sebentar di <strong>Meja {{ $transaksi->meja->no_meja ?? '-' }}</strong>.</p>
        @else
            <div class="status-icon selesai">
                <i class="bi bi-check2-circle"></i>
            </div>
            <h4 class="fw-bold text-dark">Pesanan Siap!</h4>
            <p class="text-muted small mb-0 px-2">Pesanan Anda telah selesai dibuat dan sedang diantarkan ke meja Anda. Selamat menikmati!</p>
        @endif

        <button class="btn-refresh mt-4" onclick="window.location.reload();">
            <i class="bi bi-arrow-clockwise me-1"></i> Perbarui Status
        </button>
    </div>

    <div class="invoice-card">
        <h6 class="fw-bold text-dark mb-4">Rincian Invoice</h6>
        
        <div class="invoice-row text-muted">
            <span>No. Invoice</span>
            <span class="text-dark fw-bold">{{ $transaksi->no_invoice }}</span>
        </div>
        <div class="invoice-row text-muted">
            <span>Atas Nama</span>
            <span class="text-dark fw-bold">{{ $transaksi->nama_pelanggan }}</span>
        </div>
        <div class="invoice-row text-muted">
            <span>Waktu Pesan</span>
            <span class="text-dark fw-bold">{{ $transaksi->created_at->format('H:i') }} WIB</span>
        </div>

        <div class="invoice-divider"></div>

        @foreach($transaksi->detailTransaksis as $item)
        <div class="invoice-row">
            <span>{{ $item->qty }}x {{ $item->menu->nama_menu }}</span>
            <span class="fw-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
        </div>
        @endforeach

        <div class="invoice-divider"></div>

        <div class="invoice-row mt-3">
            <span class="fw-bold fs-6">Total Tagihan</span>
            <span class="fw-bold text-success fs-5">Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</span>
        </div>

        <div class="alert alert-warning border-0 small text-start mt-4 mb-3 rounded-3" style="background-color: #FFF7ED; color: #FE6807;">
            <i class="bi bi-info-circle-fill me-1"></i> Pembayaran dilakukan di kasir setelah Anda selesai menikmati pesanan.
        </div>

        <div class="d-grid gap-2 mt-2">
            <a href="{{ route('order.download-pdf', $transaksi->id) }}" class="btn text-white fw-bold py-2" style="background-color: #198754; border-radius: 10px;">
                📄 Download Struk PDF
            </a>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // AUTO REFRESH KEAJAIBAN: 
    // Halaman akan otomatis me-refresh dirinya sendiri setiap 10 detik.
    // Jadi pelanggan tidak perlu repot klik tombol refresh manual.
    // Jika statusnya sudah selesai, hentikan auto-refresh agar tidak membebani server.
    
    var statusSaatIni = "{{ $transaksi->status_pesanan }}";
    
    if (statusSaatIni === 'Diproses') {
        setTimeout(function() {
            window.location.reload();
        }, 10000); // 10000 ms = 10 detik
    }
</script>
@endpush