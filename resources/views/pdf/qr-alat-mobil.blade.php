<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>QR Alat - {{ $mobil->nomor_plat ?? 'Mobil' }}</title>
    <style>
        .grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            justify-items: center;
            position: relative;
        }

        .item {
            width: 160px;
            text-align: center;
            page-break-inside: avoid;
            padding: 6px;
            border-radius: 6px;
            position: relative;
            /* garis potong tipis di sekeliling kotak */
            border: 1px dashed #999;
        }

        .item img {
            width: 140px;
            height: 140px;
            display: block;
            margin: 0 auto;
        }

        .name {
            margin-top: 6px;
            font-weight: 600;
            font-size: 13px;
        }

        .code {
            font-size: 11px;
            color: #666;
        }

        @media print {
            body {
                padding: 0;
            }

            .item {
                /* pastikan border tetap tampil saat print */
                border: 1px dashed #999;
            }
        }
    </style>

</head>

<body>
    <h2>QR Alat — {{ $mobil->nomor_plat ?? 'Mobil' }}</h2>

    <div class="grid">
        @foreach($alats as $alat)
        <div class="item">
            {{-- gunakan base64 svg yg udah disiapkan di controller --}}
            <img src="data:image/svg+xml;base64,{{ $qrSvgs[$alat->id] ?? '' }}" alt="QR {{ $alat->nama_alat }}">
            <div class="name">{{ $alat->nama_alat }}</div>
            <div class="code">{{ $alat->kode_barcode }}</div>
        </div>
        @endforeach
    </div>
</body>

</html>