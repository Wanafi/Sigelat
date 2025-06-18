<?php

namespace App\Filament\Resources\Laporan\RiwayatResource\Pages;

use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\Laporan\RiwayatResource;

class ViewRiwayat extends ViewRecord
{
    protected static string $resource = RiwayatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
{
    $record = $this->record;
    $related = $record->riwayatable;

    $items = [
        TextEntry::make('user.name')->label('Pelapor'),
        TextEntry::make('status')->label('Status'),
        TextEntry::make('tanggal_cek')->label('Tanggal Cek'),
        TextEntry::make('aksi')->label('Aksi'),
        TextEntry::make('catatan')->label('Catatan'),
    ];

    if ($related instanceof \App\Models\Gelar) {
        $items[] = TextEntry::make('riwayatable.mobil.nomor_plat')->label('Nomor Plat Mobil');
        $items[] = TextEntry::make('riwayatable.lokasi')->label('Lokasi');
    } elseif ($related instanceof \App\Models\Mobil) {
        $items[] = TextEntry::make('riwayatable.nomor_plat')->label('Nomor Plat');
        $items[] = TextEntry::make('riwayatable.keterangan')->label('Keterangan Mobil');
    } elseif ($related instanceof \App\Models\Alat) {
        $items[] = TextEntry::make('riwayatable.nama')->label('Nama Alat');
        $items[] = TextEntry::make('riwayatable.kondisi')->label('Kondisi Alat');
    } else {
        $items[] = TextEntry::make('riwayatable_type')->label('Jenis Laporan');
    }

    return $infolist->schema($items);
}

}
