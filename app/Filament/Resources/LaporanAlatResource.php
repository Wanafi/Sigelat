<?php

namespace App\Filament\Resources;

use App\Models\Alat;
use App\Models\Riwayat;
use Filament\Forms;
use Filament\Tables;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Actions;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\LaporanAlatResource\Pages;

class LaporanAlatResource extends Resource
{
    protected static ?string $model = Alat::class;

    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $navigationLabel = 'Laporkan Alat';
    protected static ?string $modelLabel = 'Laporan Daftar Alat';
    protected static ?string $navigationIcon = 'heroicon-m-rectangle-stack';

    public static function canCreate(): bool { return false; }
    public static function canEdit(Model $record): bool { return false; }
    public static function canDelete(Model $record): bool { return false; }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_barcode')->label('Kode Barcode')->searchable(),
                Tables\Columns\TextColumn::make('nama_alat')->label('Nama Alat')->searchable(),
                Tables\Columns\TextColumn::make('kategori_alat')->label('Kategori'),
                Tables\Columns\TextColumn::make('merek_alat')->label('Merek'),
                Tables\Columns\TextColumn::make('mobil.nomor_plat')->label('Lokasi')->default('Gudang'),
                Tables\Columns\BadgeColumn::make('status_alat')
                    ->label('Status Alat')
                    ->colors([
                        'success' => 'Bagus',
                        'danger' => 'Rusak',
                        'warning' => 'Hilang',
                    ]),
                Tables\Columns\TextColumn::make('tanggal_pembelian')
                    ->label('Tanggal Pembelian')
                    ->date('d M Y'),
            ])
            ->filters([
                Tables\Filters\MultiSelectFilter::make('status_alat')
                    ->label('Filter Status')
                    ->options([
                        'Bagus' => 'Bagus',
                        'Rusak' => 'Rusak',
                        'Hilang' => 'Hilang',
                    ])
                    ->default(['Rusak', 'Hilang']),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Informasi Alat')
                ->schema([
                    TextEntry::make('nama_alat')->label('Nama Alat'),
                    TextEntry::make('kategori_alat')->label('Kategori'),
                    TextEntry::make('merek_alat')->label('Merek'),
                    TextEntry::make('kode_barcode')->label('Kode Barcode'),
                    TextEntry::make('mobil.nomor_plat')->label('Lokasi')->default('Gudang'),
                    TextEntry::make('status_alat')
                        ->label('Status')
                        ->badge()
                        ->color(fn($state) => match ($state) {
                            'Bagus' => 'success',
                            'Rusak' => 'danger',
                            'Hilang' => 'warning',
                            default => 'gray',
                        }),
                ])
                ->columns(2),

            Actions::make([
                Actions\Action::make('konfirmasi')
                    ->label('Konfirmasi Perbaikan')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn($record) => $record->status_alat !== 'Bagus')
                    ->form([
                        TextInput::make('aksi')->label('Tindakan')->required(),
                        Textarea::make('catatan')->label('Catatan')->required(),
                    ])
                    ->action(function (Model $record, array $data) {
                        $record->update(['status_alat' => 'Bagus']);

                        Riwayat::create([
                            'riwayatable_id' => $record->id,
                            'riwayatable_type' => get_class($record),
                            'user_id' => auth()->id(),
                            'status' => 'Selesai',
                            'aksi' => $data['aksi'],
                            'catatan' => $data['catatan'],
                            'tanggal_cek' => now(),
                        ]);
                    }),
            ]),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLaporanAlats::route('/'),
            'view' => Pages\ViewLaporanAlat::route('/{record}'),
        ];
    }

        public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
