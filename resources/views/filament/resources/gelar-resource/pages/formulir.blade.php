<x-filament-panels::page>
    <div id="print-area" class="page-a4">
        <style>
            .page-a4 {
                background-color: white;
                width: 210mm;
                min-height: 297mm;
                margin: auto;
                padding: 12mm 12mm;
                font-family: Arial, sans-serif;
                font-size: 8.5pt;
                color: #000;
            }

            .manual-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                border-bottom: 2px solid #00AFF0;
                padding-bottom: 8px;
                margin-bottom: 8px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                font-size: 7.5pt;
                margin-top: 6px;
                line-height: 1.1;
            }

            th,
            td {
                border: 1px solid #444;
                padding: 2px 3px;
                text-align: center;
                vertical-align: middle;
            }

            thead th {
                background-color: rgb(255, 230, 0);
                /* hanya header tabel */
            }

            .left-align {
                text-align: left;
            }

            .signature-section {
                margin-top: 25px;
                display: flex;
                justify-content: space-between;
                font-size: 9pt;
            }

            .signature-box {
                text-align: center;
                width: 45%;
            }

            .vertical-text {
                writing-mode: vertical-rl;
                transform: rotate(180deg);
                text-align: center;
                font-weight: bold;
                line-height: 1.1;
            }


            @media print {
                @page {
                    size: A4;
                    margin: 12mm 12mm;
                }

                body * {
                    visibility: hidden;
                }

                #print-area,
                #print-area * {
                    visibility: visible;
                }

                #print-area {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                }

                header,
                .fi-header,
                .fi-sidebar,
                .fi-topbar,
                .fi-page-header {
                    display: none !important;
                }
            }
        </style>

        {{-- ✅ HEADER --}}
        <div class="manual-header">
            <img src="{{ asset('images/plnt.png') }}" alt="Logo PLN" style="height: 55px;">
            <div>
                <div style="font-size: 9pt; font-style: italic;">PT PLN (Persero)</div>
                <div style="font-size: 9pt; font-style: italic;">ULP Ahmad Yani Banjarmasin</div>
                <div style="font-size: 9pt; font-style: italic;">Laporan Kegiatan Gelar Alat Operasional</div>
            </div>
        </div>

        {{-- ✅ TABEL --}}
        <table>
            <thead>
                <tr>
                    <th style="width: 4%;" rowspan="2"></th>
                    <th style="width: 4%;" rowspan="2">No</th>
                    <th style="width: 38%;" rowspan="2">
                        {{ $record->mobil->nama_tim ?? '-' }}
                        {{ $record->mobil->no_unit ?? '-' }}
                        {{ $record->mobil->nomor_plat ?? '-' }}
                    </th>
                    <th style="width: 6%;" rowspan="2">Satuan</th>
                    <th style="width: 18%;" colspan="3">KONDISI</th>
                    <th style="width: 30%;" rowspan="2">Ket</th>
                </tr>
                <tr>
                    <th style="width: 6%;">B</th>
                    <th style="width: 6%;">R</th>
                    <th style="width: 6%;">H</th>
                </tr>
            </thead>
            <tbody>
                @php
                $grouped = $record->detailGelars->groupBy(fn($item) => $item->alat->kategori_alat ?? 'Lain-lain');
                $rowNumber = 1;
                @endphp

                @foreach ($grouped as $kategori => $items)
                @foreach ($items as $index => $detail)
                <tr>
                    @if ($index === 0)
                    <td class="vertical-text" rowspan="{{ $items->count() }}">
                        {{ strtoupper(str_replace('_', ' ', $kategori)) }}
                    </td>
                    @endif
                    <td>{{ $rowNumber++ }}</td>
                    <td class="left-align">{{ $detail->alat->nama_alat ?? '-' }}</td>
                    <td>{{ $detail->alat->satuan ?? 'bh' }}</td>
                    <td>{{ $detail->status_alat == 'Bagus' ? '✔' : '' }}</td>
                    <td>{{ $detail->status_alat == 'Rusak' ? '✔' : '' }}</td>
                    <td>{{ $detail->status_alat == 'Hilang' ? '✔' : '' }}</td>
                    <td class="left-align">{{ $detail->keterangan ?? '-' }}</td>
                </tr>
                @endforeach
                @endforeach
            </tbody>


        </table>

        {{-- ✅ TANDA TANGAN --}}
        @php
        $riwayat = $gelar->riwayatableRiwayatTerbaru ?? null;
        @endphp

        @if ($gelar->riwayats()->exists())
        @php
        $riwayat = $gelar->riwayats()->with('user')->latest()->first();
        $tanggalCetak = \Carbon\Carbon::parse($riwayat->tanggal_cek)->translatedFormat('d F Y');
        @endphp

        <div style="margin-top: 5px; font-size: 8pt;">
            {{-- Header lokasi & tanggal --}}
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                <div style="text-align: left;">
                    PT PLN (Persero) UP3 Banjarmasin<br>
                    ULP AHMAD YANI
                </div>
                <div style="text-align: right;">
                    Banjarmasin, {{ $tanggalCetak }}<br>
                    PT PLN Nusa Daya UL Banjarmasin<br>
                    ULP AHMAD YANI
                </div>
            </div>

            {{-- Tanda tangan --}}
            <div style="display: flex; justify-content: space-between; margin-top: 5px;">
                {{-- Kiri --}}
                <div style="width: 48%; text-align: center;">
                    <div style="margin-bottom: 40px;">
                        {{-- <img src="{{ asset('images/ttd_budi.png') }}" style="height: 35px;"> --}}
                    <div style="width: 45%; font-size: 8pt;">
                        <strong style="display: block;">NAUFAL NAJWAN</strong>
                        <div style="border-top: 1px solid #000; width: 200%; margin-top: 2px;"></div>
                        <div style="margin-top: 2px;">Team Leader K3LK</div>
                    </div>
                    </div>
                    <div>
                        {{-- <img src="{{ asset('images/ttd_fahrul.png') }}" style="height: 35px;"> --}}
                    <div style="width: 45%; font-size: 8pt;">
                        <strong style="display: block;">NAUFAL NAJWAN</strong>
                        <div style="border-top: 1px solid #000; width: 200%; margin-top: 2px;"></div>
                        <div style="margin-top: 2px;">Team Leader K3LK</div>
                    </div>
                    </div>
                </div>

                {{-- Kanan --}}
                <div style="width: 48%; text-align: center;">
                    <div style="margin-bottom: 40px;">
                        {{-- <img src="{{ asset('images/ttd_nari.png') }}" style="height: 35px;"> --}}
                    <div style="width: 45%; font-size: 8pt;">
                        <strong style="display: block;">NAUFAL NAJWAN</strong>
                        <div style="border-top: 1px solid #000; width: 200%; margin-top: 2px;"></div>
                        <div style="margin-top: 2px;">Team Leader K3LK</div>
                    </div>
                    </div>
                    <div style="width: 45%; font-size: 8pt;">
                        <strong style="display: block;">NAUFAL NAJWAN</strong>
                        <div style="border-top: 1px solid #000; width: 200%; margin-top: 2px;"></div>
                        <div style="margin-top: 2px;">Team Leader K3LK</div>
                    </div>
                </div>
            </div>
        </div>
        @endif


        {{-- ✅ JS Print Trigger --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Livewire.on('triggerPrint', () => {
                    window.print();
                });
            });
        </script>
</x-filament-panels::page>