<?php

namespace App\Filament\Resources\LaporanResource\Widgets;

use App\Filament\Resources\Manajemen\MobilResource;
use App\Models\Alat;
use App\Models\Gelar;
use App\Models\Mobil;
use App\Models\Laporan;
use App\Models\User;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card\Action;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class LaporanOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            stat::make('Rekapitulasi Jumlah Pengguna', User::count())
                ->description('Total Pengguna')
                ->descriptionIcon('heroicon-o-user')
                ->color('success'),
    
            stat::make('Riwayat Penggunaan Alat', $this->getRusakHabisCount())
                ->description('Laporan Alat')
                ->descriptionIcon('heroicon-o-wrench')
                ->color('danger')
                ->url(route('filament.admin.resources.laporan-alats.index')),
        
            stat::make('Aktivitas Operasional Mobil', $this->getTidakaktifPerbaikanCount())
                ->description('Laporan Mobil')
                ->descriptionIcon('heroicon-o-truck')
                ->color('warning')
                ->url(route('filament.admin.resources.laporan-mobils.index')),

            stat::make('Distribusi Gelar Alat Operasional', $this->gettidaklengkapprosesCount())
                ->description('Kegiatan Gelar Alat')
                ->descriptionIcon('heroicon-o-clipboard')
                ->color('info')
                ->url(route('filament.admin.resources.laporan-gelars.index')),
        ];
    }

    protected function getRusakHabisCount()
    {
        // Asumsi bahwa ada kolom 'status' dengan nilai 'rusak' atau 'habis'
        return Alat::whereIn('status_alat', ['rusak', 'habis'])->count();
    }

    protected function getTidakaktifPerbaikanCount()
    {
        // Asumsi bahwa ada kolom 'status' dengan nilai 'rusak' atau 'habis'
        return Mobil::whereIn('status_mobil', ['TidakAktif', 'DalamPerbaikan'])->count();
    }

    protected function gettidaklengkapprosesCount()
    {
        // Asumsi bahwa ada kolom 'status' dengan nilai 'rusak' atau 'habis'
        return Gelar::whereIn('status', ['TidakLengkap'])->count();
    }
}
