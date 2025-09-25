<!-- resources/views/alat/print-qr.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Print QR {{ $alat->nama_alat }}</title>
    <style>
        @page {
            size: auto;   /* biar ukuran menyesuaikan konten */
            margin: 0;    /* hapus margin bawaan printer */
        }
        body {
            margin: 0;
            padding: 0;
            text-align: center;
        }
        .qrcode {
            display: inline-block;
            padding: 10px;
        }
        img {
            width: 150px; /* kecilkan ukuran */
            height: 150px;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="qrcode">
        <img src="{{ $qrCode }}" alt="QR Code">
        <!-- Kalau nggak mau ada teks kode, hapus baris bawah -->
        <p style="margin:0;font-size:12px;">{{ $alat->kode_barcode }}</p>
    </div>
</body>
</html>
