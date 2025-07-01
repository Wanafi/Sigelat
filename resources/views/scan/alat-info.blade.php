<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Informasi Alat</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 py-10 px-4">

    @if (session('success'))
        <div class="fixed top-4 right-4 bg-green-500 text-white text-sm px-4 py-2 rounded shadow z-50">
            {{ session('success') }}
        </div>
    @endif

    @php
        $aksesDiizinkan = session('akses_diizinkan');
    @endphp

    <div class="max-w-2xl mx-auto bg-white p-6 rounded-xl shadow-lg border border-gray-200">
        <h1 class="text-3xl font-bold mb-6 text-gray-800 border-b pb-2">📦 Informasi Alat</h1>

        <div class="space-y-4 text-gray-700">
            <div>
                <p class="text-sm font-semibold text-gray-500">Nama Alat</p>
                <p class="text-lg">{{ $alat->nama_alat }}</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm font-semibold text-gray-500">Kategori</p>
                    <p class="text-base">{{ $alat->kategori_alat }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-500">Merek</p>
                    <p class="text-base">{{ $alat->merek_alat }}</p>
                </div>
            </div>

            <div>
                <p class="text-sm font-semibold text-gray-500">Status</p>
                <span class="inline-block px-3 py-1 rounded-full text-white text-sm
                    {{ $alat->status_alat === 'Bagus' ? 'bg-green-500' : ($alat->status_alat === 'Rusak' ? 'bg-yellow-500' : 'bg-red-500') }} ">
                    {{ $alat->status_alat }}
                </span>
            </div>

            @if ($alat->mobil)
                <div>
                    <p class="text-sm font-semibold text-gray-500">Digunakan di Mobil</p>
                    <p class="text-base">{{ $alat->mobil->nomor_plat }}</p>
                </div>
            @endif

            <div>
                <p class="text-sm font-semibold text-gray-500">Spesifikasi</p>
                <div class="prose max-w-none">
                    {!! Illuminate\Mail\Markdown::parse($alat->spesifikasi) !!}
                </div>
            </div>
        </div>

        {{-- Form password --}}
        @if (!$aksesDiizinkan)
            <form method="POST" action="{{ route('scan.barcode.verifikasi', $alat->id) }}" class="mt-6">
                @csrf
                <label class="block text-sm font-semibold text-gray-500 mb-1">Masukkan Password Admin</label>
                <input type="password" name="akses_password" placeholder="••••••••" required
                    class="border border-gray-300 rounded px-3 py-2 w-full mb-2" />

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
                    Verifikasi Akses
                </button>

                @if (session('akses_error'))
                    <p class="text-red-500 text-sm mt-2">{{ session('akses_error') }}</p>
                @endif
            </form>
        @endif

        {{-- Tombol update status hanya jika sudah verifikasi --}}
        @if ($aksesDiizinkan)
            <hr class="my-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-700 mb-3">🔄 Perbarui Status Alat</h2>

                <div class="flex flex-wrap gap-3">
                    @foreach (['Bagus' => 'green', 'Rusak' => 'yellow', 'Hilang' => 'red'] as $status => $color)
                        <form method="POST" action="{{ route('scan.barcode.update-status', $alat->id) }}">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="{{ $status }}">
                            <button type="submit" class="bg-{{ $color }}-500 hover:bg-{{ $color }}-600 text-white text-sm px-4 py-2 rounded shadow">
                                Tandai {{ $status }}
                            </button>
                        </form>
                    @endforeach
                </div>
            </div>
        @endif

        <p class="mt-10 text-center text-sm text-gray-400">© {{ date('Y') }} Inventaris PLN Ahmad Yani</p>
    </div>

</body>
</html>
