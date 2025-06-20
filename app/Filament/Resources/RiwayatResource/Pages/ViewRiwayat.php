<?php

namespace App\Filament\Resources\Laporan\RiwayatResource\Pages;

use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\Laporan\RiwayatResource;
use App\Models\Alat;
use App\Models\Mobil;
use App\Models\Gelar;

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
            TextEntry::make('tanggal_cek')->label('Tanggal Cek')->date(),
            TextEntry::make('aksi')->label('Aksi'),
            TextEntry::make('catatan')->label('Catatan'),
        ];

        if ($related instanceof Gelar) {
            $items[] = TextEntry::make('riwayatable.mobil.nomor_plat')->label('Nomor Plat Mobil');
            $items[] = TextEntry::make('riwayatable.status')->label('Status Gelar');
            $items[] = TextEntry::make('riwayatable.tanggal_cek')->label('Tanggal Gelar')->date();
        } elseif ($related instanceof Mobil) {
            $items[] = TextEntry::make('riwayatable.nomor_plat')->label('Nomor Plat Mobil');
            $items[] = TextEntry::make('riwayatable.merk_mobil')->label('Merk');
            $items[] = TextEntry::make('riwayatable.status_mobil')->label('Status Mobil');
        } elseif ($related instanceof Alat) {
            $items[] = TextEntry::make('riwayatable.nama_alat')->label('Nama Alat');
            $items[] = TextEntry::make('riwayatable.kode_barcode')->label('Kode Alat');
            $items[] = TextEntry::make('riwayatable.status_alat')->label('Status Alat');
        } else {
            $items[] = TextEntry::make('riwayatable_type')->label('Jenis Laporan');
        }

        return $infolist->schema($items);
    }
}
