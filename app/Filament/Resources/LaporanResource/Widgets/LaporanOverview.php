<?php

namespace App\Filament\Resources\LaporanResource\Widgets;

use App\Models\Alat;
use App\Models\Gelar;
use App\Models\Mobil;
use App\Models\Laporan;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card\Action;
class LaporanOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Card::make('Alat Rusak/Habis', $this->getRusakHabisCount())
                ->description('Jumlah Laporan Alat 🛠️')
                ->icon('heroicon-o-wrench')
                ->color('danger')
                ->chart([17, 2, 10, 3, 15, 4, 17])
                ->url(route('filament.admin.resources.laporan-alats.index')),
        

            Card::make('Laporan Mobil ', Mobil::count())
                ->description('Jumlah Laporan Mobil 🚗')
                ->icon('heroicon-o-truck')
                ->color('success')
                ->chart([7, 20, 10, 39, 15, 4, 17])
                ->url(route('filament.admin.resources.laporan-mobils.index')),

            Card::make('Laporan Gelar Alat', Gelar::count())
                ->description('Laporan Kegiatan Gelar Alat 📄')
                ->icon('heroicon-o-clipboard')
                ->color('info')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->url(route('filament.admin.resources.laporan-gelars.index')),
        ];
    }

    protected function getRusakHabisCount()
    {
        // Asumsi bahwa ada kolom 'status' dengan nilai 'rusak' atau 'habis'
        return Alat::whereIn('status_alat', ['rusak', 'habis'])->count();
    }
}
