<x-filament-panels::page>
    <div id="print-area" class="page-a4">
        <style>
            .page-a4 {
                background-color: white;
                width: 210mm;
                min-height: 297mm;
                margin: auto;
                padding: 15mm 15mm;
                font-family: Arial, sans-serif;
                font-size: 9pt;
                color: #000;
            }

            .manual-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                border-bottom: 2px solid #004c97;
                padding-bottom: 10px;
                margin-bottom: 10px;
            }

            .manual-header div {
                text-align: left;
            }

            .manual-body {
                line-height: 1.2;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                font-size: 8.5pt;
                margin-top: 10px;
            }

            th, td {
                border: 1px solid #444;
                padding: 2px 4px;
                text-align: center;
                vertical-align: middle;
                word-wrap: break-word;
            }

            th {
                background-color: #e6f0ff;
                color: #004c97;
            }

            .left-align {
                text-align: left;
            }

            .signature-section {
                margin-top: 30px;
                display: flex;
                justify-content: space-between;
                font-size: 9pt;
            }

            .signature-box {
                text-align: center;
                width: 45%;
            }

            @media print {
                @page {
                    size: A4;
                    margin: 15mm 15mm;
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
                }

                header, .fi-header, .fi-sidebar, .fi-topbar, .fi-page-header {
                    display: none !important;
                }
            }
        </style>

        {{-- ✅ HEADER --}}
        <div class="manual-header">
            <img src="{{ asset('images/plnt.png') }}" alt="Logo PLN" style="height: 60px;">
            <div>
                <div style="font-size: 11pt; font-style: italic;">PT PLN (Persero)</div>
                <div style="font-size: 12pt; font-weight: bold;">ULP Ahmad Yani Banjarmasin</div>
                <div style="font-size: 10pt; color: #004c97;">Laporan Kegiatan Gelar Alat Operasional</div>
            </div>
        </div>

        {{-- ✅ BODY --}}
        <div class="manual-body">
            <p><strong>Tanggal Pemeriksaan:</strong> {{ \Carbon\Carbon::parse($gelar->tanggal_cek)->translatedFormat('d F Y') }}</p>
            <p><strong>Nomor Kendaraan:</strong> {{ $gelar->mobil->nomor_plat ?? '-' }}</p>
            <p><strong>Status Pemeriksaan:</strong> {{ $gelar->status }}</p>

            <div style="font-weight: bold; margin-top: 10px; font-size: 11pt; color: #004c97; border-left: 5px solid #fdb913; padding-left: 8px;">
                Daftar Alat yang Diperiksa
            </div>

            <table>
                <thead>
                    <tr>
                        <th style="width: 4%;">No</th>
                        <th style="width: 40%;">Nama Alat</th>
                        <th style="width: 8%;">Satuan</th>
                        <th style="width: 6%;">Vol</th>
                        <th style="width: 6%;">B</th>
                        <th style="width: 6%;">R</th>
                        <th style="width: 6%;">H</th>
                        <th style="width: 24%;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($gelar->detailGelars as $index => $detail)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="left-align">{{ $detail->alat->nama_alat ?? '-' }}</td>
                        <td>{{ $detail->alat->satuan ?? 'bh' }}</td>
                        <td>1</td>
                        <td>{{ $detail->status_alat == 'Bagus' ? '✔' : '' }}</td>
                        <td>{{ $detail->status_alat == 'Rusak' ? '✔' : '' }}</td>
                        <td>{{ $detail->status_alat == 'Hilang' ? '✔' : '' }}</td>
                        <td class="left-align">{{ $detail->keterangan ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="signature-section">
                <div class="signature-box">
                    Pemeriksa,<br><br><br><br>
                    <u>____________________</u><br>
                    (Nama Pemeriksa)
                </div>
                <div class="signature-box">
                    Petugas Mobil,<br><br><br><br>
                    <u>____________________</u><br>
                    (Nama Petugas)
                </div>
            </div>
        </div>
    </div>

    {{-- JS Print Trigger --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Livewire.on('triggerPrint', () => {
                window.print();
            });
        });
    </script>
</x-filament-panels::page>
