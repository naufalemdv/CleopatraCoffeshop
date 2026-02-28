@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="container-fluid p-0">
    
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm p-4">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr class="text-muted small">
                        <th>NO. INVOICE</th>
                        <th>TANGGAL</th>
                        <th>TOTAL</th>
                        <th class="text-center">STATUS</th>
                        <th class="text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksis as $t)
                    <tr>
                        <td class="fw-bold">{{ $t->no_invoice }}</td>
                        <td class="small text-muted">{{ $t->created_at->format('d/m/Y H:i') }}</td>
                        <td class="fw-bold">Rp {{ number_format($t->total_bayar, 0, ',', '.') }}</td>
                        <td class="text-center">
                            @if($t->status_pembayaran == 'Belum Bayar')
                                <span class="badge rounded-pill px-3" style="background-color: #FFF7ED; color: #FE6807; border: 1px solid #FE6807;">
                                    Belum Bayar
                                </span>
                            @else
                                <span class="badge bg-success-light text-success rounded-pill px-3">
                                    <i class="bi bi-check2-all me-1"></i> Selesai
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                
                                @if($t->status_pembayaran == 'Belum Bayar')
                                <form action="{{ route('admin.transaksi.bayar', $t->id) }}" method="POST" onsubmit="return confirm('Tandai pembayaran telah Selesai/Lunas?')">
                                    @csrf 
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm text-white shadow-sm" style="background-color: #198754;" title="Tandai Sudah Bayar">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </button>
                                </form>
                                @endif

                                <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $t->id }}" title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </button>
                                
                                <form action="{{ route('admin.transaksi.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Hapus transaksi ini?')">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light text-danger" title="Hapus Riwayat">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <div class="modal fade" id="modalDetail{{ $t->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg">
                                <div class="modal-header">
                                    <h5 class="fw-bold mb-0">Detail Pesanan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <div class="d-flex justify-content-between mb-3 bg-light p-2 rounded">
                                        <span>Invoice: <strong>{{ $t->no_invoice }}</strong></span>
                                        <span>Meja: <strong>{{ $t->meja->no_meja ?? '-' }}</strong></span>
                                    </div>
                                    
                                    @foreach($t->detailTransaksis as $item)
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>{{ $item->qty }}x {{ $item->menu->nama_menu }}</span>
                                        <span class="fw-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                    </div>
                                    @endforeach
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-bold fs-5">Total Akhir</span>
                                        <span class="fw-bold fs-5 text-dark">Rp {{ number_format($t->total_bayar, 0, ',', '.') }}</span>
                                    </div>
                                    
                                    <div class="mt-3 text-center">
                                        @if($t->status_pembayaran == 'Belum Bayar')
                                            <span class="text-danger small fw-bold"><i class="bi bi-info-circle me-1"></i> Pelanggan ini belum melakukan pembayaran di kasir.</span>
                                        @else
                                            <span class="text-success small fw-bold"><i class="bi bi-check-circle me-1"></i> Pembayaran telah diselesaikan.</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="modal-footer border-0 p-4 pt-0">
                                    <a href="{{ route('admin.transaksi.print', $t->id) }}" target="_blank" class="btn btn-custom w-100 py-3 fw-bold rounded-pill">
                                        <i class="bi bi-printer me-2"></i> CETAK STRUK
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection