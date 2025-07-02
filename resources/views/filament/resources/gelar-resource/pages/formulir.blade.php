<x-filament-panels::page>

    <div id="print-area">
        <style>
            @media print {
                @page {
                    size: A4 portrait;
                    margin: 20mm;
                }

                body {
                    margin: 0;
                    padding: 0;
                }

                body * {
                    visibility: hidden;
                }

                #print-area, #print-area * {
                    visibility: visible;
                }

                #print-area {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                }

                .fixed-header, .fixed-footer {
                    position: fixed;
                    left: 0;
                    right: 0;
                    background: #fff;
                    padding: 10px 40px;
                    z-index: 999;
                }

                .fixed-header {
                    top: 0;
                    border-bottom: 2px solid #004c97;
                }

                .fixed-footer {
                    bottom: 0;
                    border-top: 2px solid #004c97;
                    font-size: 10pt;
                    text-align: center;
                    font-style: italic;
                    color: #555;
                }

                .print-body {
                    margin-top: 100px;
                    margin-bottom: 80px;
                    padding: 0 40px;
                }
            }
        </style>

        {{-- HEADER CETAK --}}
        <div class="fixed-header" style="display: flex; align-items: center; justify-content: space-between;">
            <img src="{{ asset('images/plnt.png') }}" alt="Logo PLN" style="height: 60px;">
            <div style="text-align: left;">
                <div style="font-size: 12pt; font-style: italic;">PT PLN (Persero)</div>
                <div style="font-size: 13pt; font-weight: bold;">ULP Ahmad Yani Banjarmasin</div>
                <div style="font-size: 11pt; color: #004c97;">Laporan Kegiatan Gelar Alat Operasional</div>
            </div>
        </div>

        {{-- BODY CETAK --}}
        <div class="print-body" style="font-family: 'Arial', sans-serif; font-size: 11pt; color: #000;">

            <p><strong>Tanggal Pemeriksaan:</strong> {{ \Carbon\Carbon::parse($gelar->tanggal_cek)->translatedFormat('d F Y') }}</p>
            <p><strong>Nomor Kendaraan:</strong> {{ $gelar->mobil->nomor_plat ?? '-' }}</p>
            <p><strong>Status Pemeriksaan:</strong> {{ $gelar->status }}</p>

            <div style="font-weight: bold; margin-top: 20px; font-size: 13pt; color: #004c97; border-left: 5px solid #fdb913; padding-left: 10px;">
                Daftar Alat yang Diperiksa
            </div>

            <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                <thead>
                    <tr style="background-color: #e6f0ff; color: #004c97;">
                        <th style="border: 1px solid #888; padding: 6px;">No</th>
                        <th style="border: 1px solid #888; padding: 6px;">Nama Alat</th>
                        <th style="border: 1px solid #888; padding: 6px;">Kategori</th>
                        <th style="border: 1px solid #888; padding: 6px;">Merek</th>
                        <th style="border: 1px solid #888; padding: 6px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gelar->detailGelars as $index => $detail)
                    <tr>
                        <td style="border: 1px solid #888; padding: 6px;">{{ $index + 1 }}</td>
                        <td style="border: 1px solid #888; padding: 6px;">{{ $detail->alat->nama_alat ?? '-' }}</td>
                        <td style="border: 1px solid #888; padding: 6px;">{{ $detail->alat->kategori_alat ?? '-' }}</td>
                        <td style="border: 1px solid #888; padding: 6px;">{{ $detail->alat->merek_alat ?? '-' }}</td>
                        <td style="border: 1px solid #888; padding: 6px;">{{ $detail->status_alat }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; border: 1px solid #888; padding: 6px;">Tidak ada data alat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div style="margin-top: 50px; display: flex; justify-content: space-between; text-align: center;">
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

        {{-- FOOTER CETAK --}}
        <div class="fixed-footer">
            Formulir ini dicetak sebagai bagian dari dokumentasi internal PT PLN (Persero). Pastikan data alat sesuai kenyataan di lapangan.
        </div>
    </div>

    {{-- Trigger Print --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Livewire.on('triggerPrint', () => {
                window.print();
            });
        });
    </script>


</x-filament-panels::page>

