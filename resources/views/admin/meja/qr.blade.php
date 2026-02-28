<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code - Meja {{ $meja->no_meja }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/qr-print.css') }}">
</head>
<body onload="window.print();">

    <div class="qr-card">
        <div class="brand-title">COFFEESHOP</div>
        <div class="subtitle">Scan untuk melihat menu & memesan</div>

        <div class="qr-container">
            {!! $qrCode !!}
        </div>

        <div class="table-badge">
            MEJA {{ $meja->no_meja }}
        </div>

        <div class="instructions">
            Sebelum scan QR code, sambungkan dulu Wi-Fi ke<br>
            SSID: <strong>COFFESHOP-WIFI</strong> | Password: <strong>coffeshop123</strong>
        </div>

        <div class="instructions" style="border-top: 1px dashed #eee; padding-top: 12px;">
            Buka kamera HP Anda, arahkan ke QR Code di atas,<br>
            lalu klik tautan yang muncul di layar.
        </div>
    </div>

</body>
</html>