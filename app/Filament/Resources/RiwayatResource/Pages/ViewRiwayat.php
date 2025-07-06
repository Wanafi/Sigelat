<?php

namespace App\Filament\Resources\RiwayatResource\Pages;

use App\Filament\Resources\RiwayatResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\TextEntry;

class ViewRiwayat extends ViewRecord
{
    protected static string $resource = RiwayatResource::class;

    public function getHeaderInfolist(): ?Infolist
    {
        return Infolist::make()
            ->schema([
                Section::make('Detail Riwayat')
                    ->description('Informasi lengkap mengenai aktivitas atau laporan yang telah dikonfirmasi.')
                    ->schema([
                        Group::make([
                            TextEntry::make('user.name')
                                ->label('Pelapor')
                                ->icon('heroicon-o-user'),

                            TextEntry::make('riwayatable_type')
                                ->label('Jenis Laporan')
                                ->formatStateUsing(fn($state) => match($state) {
                                    'App\\Models\\Mobil' => 'Mobil',
                                    'App\\Models\\Alat' => 'Alat',
                                    'App\\Models\\Gelar' => 'Gelar',
                                    default => 'Lainnya',
                                })
                                ->badge()
                                ->color(fn($state) => match($state) {
                                    'App\\Models\\Mobil' => 'info',
                                    'App\\Models\\Alat' => 'success',
                                    'App\\Models\\Gelar' => 'warning',
                                    default => 'gray',
                                })
                                ->icon(fn($state) => match($state) {
                                    'App\\Models\\Mobil' => 'heroicon-o-truck',
                                    'App\\Models\\Alat' => 'heroicon-o-wrench',
                                    'App\\Models\\Gelar' => 'heroicon-o-map',
                                    default => 'heroicon-o-question-mark-circle',
                                }),

                            TextEntry::make('tanggal_cek')
                                ->label('Tanggal Cek')
                                ->date()
                                ->icon('heroicon-o-calendar'),

                            TextEntry::make('status')
                                ->label('Status')
                                ->badge()
                                ->icon(fn($state) => $state === 'Selesai' ? 'heroicon-o-check-circle' : 'heroicon-o-clock')
                                ->color(fn($state) => $state === 'Selesai' ? 'success' : 'gray'),
                        ])->columns(2),

                        Group::make([
                            TextEntry::make('aksi')
                                ->label('Aksi yang Dilakukan')
                                ->default('-'),

                            TextEntry::make('catatan')
                                ->label('Catatan Tambahan')
                                ->default('-')
                                ->markdown(),

                            TextEntry::make('riwayatable_id')
                                ->label('ID Terkait')
                                ->url(fn($record) => match($record->riwayatable_type) {
                                    'App\\Models\\Mobil' => route('filament.admin.resources.mobils.view', $record->riwayatable_id),
                                    'App\\Models\\Alat' => route('filament.admin.resources.alats.view', $record->riwayatable_id),
                                    'App\\Models\\Gelar' => route('filament.admin.resources.gelars.view', $record->riwayatable_id),
                                    default => null,
                                })
                                ->openUrlInNewTab()
                                ->icon('heroicon-o-link')
                                ->hidden(fn($record) => !in_array($record->riwayatable_type, [
                                    'App\\Models\\Mobil',
                                    'App\\Models\\Alat',
                                    'App\\Models\\Gelar',
                                ])),
                        ])->columns(1),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }
}
