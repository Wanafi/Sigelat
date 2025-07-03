<x-filament-panels::page>
<div id="print-area" class="page-a4">
    <style>
        .page-a4 {
            width: 210mm;
            min-height: 297mm;
            margin: auto;
            padding: 20mm 25mm;
            background-color: white;
            display: flex;
            flex-direction: column;
            font-family: Arial, sans-serif;
            font-size: 10pt;
        }

        .header, .info, .signature {
            flex-shrink: 0;
        }

        .table-wrapper {
            flex-grow: 1;
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
        }

        th, td {
            border: 1px solid #999;
            padding: 3px 6px;
            word-break: break-word;
        }

        thead {
            background-color: #e6f0ff;
            color: #004c97;
        }

        .signature {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            text-align: center;
        }

        @media print {
            @page {
                size: A4;
                margin: 20mm 25mm;
            }

            html, body {
                height: auto;
                margin: 0 !important;
                padding: 0 !important;
            }

            body * {
                visibility: hidden;
            }

            #print-area, #print-area * {
                visibility: visible;
            }

            #print-area {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                margin: 0;
                background-color: white;
            }
        }
    </style>

    {{-- ✅ HEADER --}}
    <div class="header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #004c97; padding-bottom: 10px;">
        <img src="{{ asset('images/plnt.png') }}" alt="Logo PLN" style="height: 60px;">
        <div style="text-align: right;">
            <div style="font-size: 12pt; font-style: italic;">PT PLN (Persero)</div>
            <div style="font-size: 13pt; font-weight: bold;">ULP Ahmad Yani Banjarmasin</div>
            <div style="font-size: 11pt; color: #004c97;">Laporan Kegiatan Gelar Alat Operasional</div>
        </div>
    </div>

    {{-- ✅ INFO --}}
    <div class="info" style="margin-top: 10px;">
        <p><strong>Tanggal Pemeriksaan:</strong> {{ \Carbon\Carbon::parse($gelar->tanggal_cek)->translatedFormat('d F Y') }}</p>
        <p><strong>Nomor Kendaraan:</strong> {{ $gelar->mobil->nomor_plat ?? '-' }}</p>
        <p><strong>Status Pemeriksaan:</strong> {{ $gelar->status }}</p>
        <div style="font-weight: bold; margin-top: 15px; font-size: 12pt; color: #004c97; border-left: 5px solid #fdb913; padding-left: 10px;">
            Daftar Alat yang Diperiksa
        </div>
    </div>

    {{-- ✅ TABEL --}}
    <div class="table-wrapper" style="margin-top: 10px;">
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 40%;">Nama Alat</th>
                    <th style="width: 20%;">Status</th>
                    <th style="width: 35%;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($gelar->detailGelars as $index => $detail)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $detail->alat->nama_alat ?? '-' }}</td>
                        <td>{{ $detail->status_alat }}</td>
                        <td>{{ $detail->keterangan ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ✅ TANDA TANGAN --}}
    <div class="signature">
        <div>
            Pemeriksa,<br><br><br><br>
            <u>____________________</u><br>
            (Nama Pemeriksa)
        </div>
        <div>
            Petugas Mobil,<br><br><br><br>
            <u>____________________</u><br>
            (Nama Petugas)
        </div>
    </div>
</div>
        


    {{-- JS Trigger Print --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Livewire.on('triggerPrint', () => {
                window.print();
            });
        });
    </script>
</x-filament-panels::page>