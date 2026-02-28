<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk_{{ $transaksi->no_invoice }}</title>
    <link rel="stylesheet" href="{{ asset('css/struk.css') }}">
</head>
<body onload="window.print();">

    <div class="header">
        <h2>CLEOPATRACOFFEE</h2>
        <p style="font-size: 11px; margin: 2px 0;">Jl. Raya Cigombong No. 123<br>Cigombong, Jawa Barat</p>
    </div>

    <div class="divider"></div>

    <table class="info-table">
        <tr>
            <td>Invoice: {{ $transaksi->no_invoice }}</td>
            <td class="text-right">Meja: {{ $transaksi->meja->no_meja ?? '-' }}</td>
        </tr>
        <tr>
            <td>Kasir: {{ $transaksi->user->nama ?? 'Admin' }}</td>
            <td class="text-right">{{ $transaksi->created_at->format('d/m/Y H:i') }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <table class="item-table">
        @foreach($transaksi->detailTransaksis as $item)
        <tr>
            <td colspan="2">{{ $item->menu->nama_menu }}</td>
        </tr>
        <tr>
            <td>{{ $item->qty }} x {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
            <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </table>

    <div class="divider"></div>

    <div class="total-section">
        <div style="display: flex; justify-content: space-between;">
            <span class="fw-bold">TOTAL AKHIR</span>
            <span class="fw-bold">Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; font-size: 12px; margin-top: 5px;">
            <span>Metode Bayar</span>
            <span>{{ $transaksi->metode_bayar }}</span>
        </div>
    </div>

    <div class="divider"></div>

    <div class="text-center" style="margin-top: 15px; font-size: 10px;">
        <p>TERIMA KASIH ATAS KUNJUNGAN ANDA!<br>Barang yang sudah dibeli tidak dapat ditukar.</p>
    </div>

</body>
</html>