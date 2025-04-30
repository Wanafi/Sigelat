<?php

namespace App\Filament\Resources\Laporan;

use Filament\Resources\Resource;
use App\Models\Laporan;

class LaporanResource extends Resource
{
    protected static ?string $model = Laporan::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-report';
    protected static ?string $navigationLabel = 'Laporan';
    protected static ?string $slug = null; // supaya tidak bikin URL utama
    protected static bool $shouldRegisterNavigation = false; // sembunyikan dari menu

    public static function getPages(): array
    {
        return [];
    }
}
