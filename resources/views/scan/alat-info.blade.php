<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Alat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.6s ease-out forwards;
        }
        .glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-indigo-50 min-h-screen">

    @if (session('success'))
        <div class="fixed top-6 right-6 bg-white shadow-xl border-l-4 border-green-500 px-6 py-4 rounded-lg z-50 flex items-center gap-3 animate-fadeIn">
            <div class="bg-green-500 p-2 rounded-full">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <span class="text-gray-800 font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @php
        $aksesDiizinkan = session('akses_diizinkan');
    @endphp

    <!-- Hero Section -->
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-indigo-600 opacity-90"></div>
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.05\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        
        <div class="relative max-w-6xl mx-auto px-4 py-16 md:py-24">
            <div class="text-center mb-12 animate-fadeIn">
                <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-white text-sm font-medium mb-6">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    Sistem Inventaris PLN Ahmad Yani
                </div>
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-4">{{ $alat->nama_alat }}</h1>
                <p class="text-lg md:text-xl text-blue-100 max-w-2xl mx-auto">Informasi lengkap dan detail spesifikasi alat inventaris</p>
            </div>

            <!-- Status Badge -->
            <div class="flex justify-center animate-fadeIn" style="animation-delay: 0.2s;">
                <div class="inline-flex items-center gap-3 glass px-6 py-4 rounded-2xl shadow-lg">
                    <span class="text-gray-600 font-medium">Status Saat Ini:</span>
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl font-semibold
                        {{ $alat->status_alat === 'Baik' ? 'bg-green-500 text-white' : ($alat->status_alat === 'Rusak' ? 'bg-yellow-400 text-gray-900' : 'bg-red-500 text-white') }}">
                        @if ($alat->status_alat === 'Baik')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        @elseif ($alat->status_alat === 'Rusak')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        @endif
                        {{ $alat->status_alat }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-4 -mt-12 pb-20">
        
        <!-- Info Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12 animate-fadeIn" style="animation-delay: 0.3s;">
            
            <!-- Kategori Card -->
            <div class="glass rounded-2xl shadow-xl p-8 border border-white/20 hover:shadow-2xl transition-shadow">
                <div class="flex items-center gap-4 mb-4">
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-3 rounded-xl">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Kategori</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $alat->kategori_alat }}</div>
                    </div>
                </div>
            </div>

            <!-- Merek Card -->
            <div class="glass rounded-2xl shadow-xl p-8 border border-white/20 hover:shadow-2xl transition-shadow">
                <div class="flex items-center gap-4 mb-4">
                    <div class="bg-gradient-to-br from-orange-500 to-orange-600 p-3 rounded-xl">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Merek</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $alat->merek_alat }}</div>
                    </div>
                </div>
            </div>

            <!-- Mobil Card -->
            <div class="glass rounded-2xl shadow-xl p-8 border border-white/20 hover:shadow-2xl transition-shadow">
                <div class="flex items-center gap-4 mb-4">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-3 rounded-xl">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Digunakan di Mobil</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $alat->mobil ? $alat->mobil->nomor_plat : '-' }}</div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Spesifikasi Section -->
        <div class="glass rounded-2xl shadow-xl p-8 md:p-10 border border-white/20 mb-12 animate-fadeIn" style="animation-delay: 0.4s;">
            <div class="flex items-center gap-3 mb-6 pb-6 border-b border-gray-200">
                <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 p-3 rounded-xl">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Spesifikasi Teknis</h2>
            </div>
            <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                {!! Illuminate\Mail\Markdown::parse($alat->spesifikasi) !!}
            </div>
        </div>

        <!-- Action Section -->
        @if (!$aksesDiizinkan)
            <!-- Lock Section -->
            <div class="glass rounded-2xl shadow-xl p-8 md:p-10 border border-white/20 max-w-2xl mx-auto animate-fadeIn" style="animation-delay: 0.5s;">
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-amber-400 to-amber-500 rounded-2xl mb-6 shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Akses Terbatas</h3>
                    <p class="text-gray-600">Masukkan password admin untuk mengubah status alat</p>
                </div>

                <form method="POST" action="{{ route('scan.barcode.verifikasi', $alat->id) }}" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Password Admin</label>
                        <input type="password" name="akses_password" placeholder="Masukkan password" required
                            class="w-full border-2 border-gray-300 rounded-xl px-5 py-4 text-lg focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all" />
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold px-6 py-4 rounded-xl shadow-lg hover:shadow-xl transition-all text-lg">
                        Verifikasi Akses
                    </button>

                    @if (session('akses_error'))
                        <div class="flex items-center gap-3 p-4 bg-red-50 border-2 border-red-200 rounded-xl">
                            <svg class="w-6 h-6 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-red-800 font-medium">{{ session('akses_error') }}</p>
                        </div>
                    @endif
                </form>
            </div>
        @endif

        @if ($aksesDiizinkan)
            <!-- Update Status Section -->
            <div class="glass rounded-2xl shadow-xl p-8 md:p-10 border border-white/20 max-w-3xl mx-auto animate-fadeIn" style="animation-delay: 0.5s;">
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl mb-6 shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">Perbarui Status Alat</h2>
                    <p class="text-gray-600">Pilih status baru untuk alat ini</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @foreach (['Baik' => ['color' => 'green', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'], 'Rusak' => ['color' => 'yellow', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'], 'Hilang' => ['color' => 'red', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z']] as $status => $data)
                        <form method="POST" action="{{ route('scan.barcode.update-status', $alat->id) }}">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="{{ $status }}">
                            <button type="submit" class="w-full bg-{{ $data['color'] }}-{{ $status === 'Rusak' ? '400' : '500' }} hover:bg-{{ $data['color'] }}-{{ $status === 'Rusak' ? '500' : '600' }} text-{{ $status === 'Rusak' ? 'gray-900' : 'white' }} font-semibold px-6 py-5 rounded-xl shadow-lg hover:shadow-xl transition-all flex flex-col items-center gap-3 text-lg">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $data['icon'] }}"></path>
                                </svg>
                                Tandai {{ $status }}
                            </button>
                        </form>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-8">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <div class="flex items-center justify-center gap-2 text-gray-600 mb-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                <span class="font-semibold">Sistem Inventaris PLN Ahmad Yani</span>
            </div>
            <p class="text-sm text-gray-500">© {{ date('Y') }} PT PLN (Persero). All rights reserved.</p>
        </div>
    </footer>

</body>
</html>