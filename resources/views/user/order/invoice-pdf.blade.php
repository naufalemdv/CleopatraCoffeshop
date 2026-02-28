<!DOCTYPE html>
<html>
<head>
    <title>Invoice Pesanan - {{ $transaksi->no_invoice }}</title>
    <style>
        body { font-family: sans-serif; color: #333; font-size: 14px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; color: #FE6807; }
        .header p { margin: 5px 0 0 0; font-size: 12px; color: #666; }
        
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 3px 0; }
        
        .item-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .item-table th, .item-table td { padding: 10px 5px; border-bottom: 1px dashed #ccc; }
        .item-table th { border-bottom: 2px solid #333; text-align: left; }
        
        .total-row td { padding-top: 15px; font-weight: bold; font-size: 16px; border-bottom: none; }
        .total-amount { color: #198754; text-align: right; }
        .text-right { text-align: right; }
        
        .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #888; font-style: italic; }
    </style>
</head>
<body>
    <div class="header">
        <h2>COFFESHOP</h2>
        <p>Jl. Contoh Alamat No. 123, Kota</p>
    </div>

    <table class="info-table">
        <tr>
            <td width="30%"><strong>No. Invoice</strong></td>
            <td>: {{ $transaksi->no_invoice }}</td>
        </tr>
        <tr>
            <td><strong>Atas Nama</strong></td>
            <td>: {{ $transaksi->nama_pelanggan }}</td>
        </tr>
        <tr>
            <td><strong>Waktu Pesan</strong></td>
            <td>: {{ $transaksi->created_at->format('d M Y - H:i') }} WIB</td>
        </tr>
        <tr>
            <td><strong>Meja</strong></td>
            <td>: {{ $transaksi->meja->no_meja ?? '-' }}</td>
        </tr>
    </table>

    <table class="item-table">
        <thead>
            <tr>
                <th width="15%">Qty</th>
                <th width="50%">Item Menu</th>
                <th width="35%" class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi->detailTransaksis as $item)
            <tr>
                <td>{{ $item->qty }}x</td>
                <td>{{ $item->menu->nama_menu }}</td>
                <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="2">Total Tagihan</td>
                <td class="total-amount">Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Harap tunjukkan struk ini saat melakukan pembayaran di Kasir.<br>
        Terima kasih atas kunjungan Anda!
    </div>
</body>
</html>