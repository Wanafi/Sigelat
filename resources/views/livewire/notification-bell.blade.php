<div class="relative">
    <button wire:click="$toggle('showDropdown')" class="relative">
        <x-filament::icon name="heroicon-o-bell" class="w-6 h-6 text-gray-600" />
        @if ($jumlahBelumDibaca > 0)
            <span class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                {{ $jumlahBelumDibaca }}
            </span>
        @endif
    </button>

    <div class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl z-50"
        x-data="{ open: @entangle('showDropdown').defer }" x-show="open" @click.away="open = false">
        <div class="p-3 border-b text-sm font-semibold">Notifikasi Terbaru</div>
        <ul class="max-h-80 overflow-y-auto divide-y">
            @forelse ($notifikasis as $notif)
                <li class="p-3 hover:bg-gray-100 text-sm">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="font-semibold">{{ $notif->judul }}</div>
                            <div class="text-gray-600">{{ $notif->pesan }}</div>
                        </div>
                        @if (! $notif->dibaca)
                            <button wire:click="tandaiDibaca({{ $notif->id }})" class="text-xs text-blue-500">Tandai dibaca</button>
                        @endif
                    </div>
                </li>
            @empty
                <li class="p-3 text-gray-500 text-sm">Tidak ada notifikasi.</li>
            @endforelse
        </ul>
    </div>
</div>
