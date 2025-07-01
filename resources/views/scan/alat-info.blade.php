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
                    {{ $alat->status_alat === 'Bagus' ? 'bg-green-500' : ($alat->status_alat === 'Rusak' ? 'bg-yellow-500' : 'bg-red-500') }}">
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

        @if (true)
            <hr class="my-6">

            <div>
                <h2 class="text-xl font-semibold text-gray-700 mb-3">🔄 Perbarui Status Alat</h2>

                <div class="flex flex-wrap gap-3">
                    <form method="POST" action="{{ route('scan.barcode.update-status', $alat->id) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="Bagus">
                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white text-sm px-4 py-2 rounded shadow">
                            Tandai Bagus
                        </button>
                    </form>

                    <form method="POST" action="{{ route('scan.barcode.update-status', $alat->id) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="Rusak">
                        <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white text-sm px-4 py-2 rounded shadow">
                            Tandai Rusak
                        </button>
                    </form>

                    <form method="POST" action="{{ route('scan.barcode.update-status', $alat->id) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="Hilang">
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-sm px-4 py-2 rounded shadow">
                            Tandai Hilang
                        </button>
                    </form>
                </div>
            </div>
        @endif

        <p class="mt-10 text-center text-sm text-gray-400">© {{ date('Y') }} Inventaris PLN Ahmad Yani</p>
    </div>

</body>
</html>
