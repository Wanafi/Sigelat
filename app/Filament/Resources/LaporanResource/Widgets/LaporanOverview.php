<?php

namespace App\Filament\Resources\LaporanResource\Widgets;

use App\Filament\Resources\MobilResource;
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
            // stat::make('Rekapitulasi Jumlah Pengguna', User::count())
            //     ->description('Total Pengguna')
            //     ->descriptionIcon('heroicon-o-user')
            //     ->color('success'),
    
            stat::make('Alat Dengan Kondisi Rusak/Hilang', $this->getRusakHilangCount())
                ->description('Laporan Alat')
                ->descriptionIcon('heroicon-o-wrench')
                ->color('danger')
                ->url(route('filament.admin.resources.laporan-alats.index')),
        
            stat::make('Mobil Dengan Kondisi Dalam Perbaikan/Tidak Aktif', $this->getTidakAktifPerbaikanCount())
                ->description('Laporan Mobil')
                ->descriptionIcon('heroicon-o-truck')
                ->color('warning')
                ->url(route('filament.admin.resources.laporan-mobils.index')),

            stat::make('Hasil Kegiatan Gelar Alat', $this->gettidaklengkapprosesCount())
                ->description('Kegiatan Gelar Alat')
                ->descriptionIcon('heroicon-o-clipboard')
                ->color('info')
                ->url(route('filament.admin.resources.laporan-gelars.index')),
        ];
    }

    protected function getRusakHilangCount()
    {
        // Asumsi bahwa ada kolom 'status' dengan nilai 'rusak' atau 'Hilang'
        return Alat::whereIn('status_alat', ['rusak', 'hilang'])->count();
    }

    protected function getTidakAktifPerbaikanCount()
    {
        // Asumsi bahwa ada kolom 'status' dengan nilai 'rusak' atau 'Hilang'
        return Mobil::whereIn('status_mobil', ['Tidak Aktif', 'Dalam Perbaikan'])->count();
    }

    protected function gettidaklengkapprosesCount()
    {
        // Asumsi bahwa ada kolom 'status' dengan nilai 'rusak' atau 'Hilang'
        return Gelar::whereIn('status', ['Tidak Lengkap'])->count();
    }
}
