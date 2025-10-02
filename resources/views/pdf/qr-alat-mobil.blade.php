<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>QR Alat - {{ $mobil->nomor_plat ?? 'Mobil' }}</title>
    <style>
        @page {
            size: A4;
            margin: 10mm;
        }

        body {
            margin: 0;
            padding: 10mm;
            font-family: Arial, sans-serif;
        }

        h2 {
            font-size: 14px;
            margin-bottom: 10mm;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(4, 50mm); /* 4 kolom x 50mm */
            gap: 5mm;
            justify-content: start;
        }

        .item {
            width: 50mm;
            height: 20mm;
            text-align: center;
            page-break-inside: avoid;
            border: 1px dashed #999;
            box-sizing: border-box;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2mm;
            position: relative;
        }

        /* garis pemisah vertikal di tengah */
        .item::before {
            content: "";
            position: absolute;
            top: 0;
            bottom: 0;
            left: 50%;
            width: 0;
            border-left: 1px dashed #999;
        }

        /* wadah 2 QR */
        .qr-pair {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            height: 100%;
            padding: 0 2mm;
            box-sizing: border-box;
        }

        /* QR container */
        .qr-container {
            width: 18mm;
            height: 18mm;
            flex-shrink: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .qr-container img {
            max-width: 100%;
            max-height: 100%;
            display: block;
        }

        @media print {
            body {
                padding: 10mm;
            }

            h2 {
                display: none; /* sembunyikan judul saat print */
            }

            .item {
                border: 1px dashed #999;
            }

            .item::before {
                border-left: 1px dashed #999;
            }

            @page {
                margin: 10mm;
            }
        }
    </style>

</head>

<body>
    <h2>QR Alat — {{ $mobil->nomor_plat ?? 'Mobil' }}</h2>

    <div class="grid">
        @foreach($alats->chunk(2) as $pair) 
        <div class="item">
            <div class="qr-pair">
                @foreach($pair as $alat)
                <div class="qr-container">
                    <img src="data:image/svg+xml;base64,{{ $qrSvgs[$alat->id] ?? '' }}" alt="QR {{ $alat->nama_alat }}">
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</body>

</html>
