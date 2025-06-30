<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Alat;
use App\Models\Riwayat;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\MultiSelectFilter;
use App\Filament\Resources\LaporanAlatResource\Pages;

class LaporanAlatResource extends Resource
{
    protected static ?string $model = Alat::class;

    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $navigationLabel = 'Laporkan Alat';
    
    protected static ?string $modelLabel = 'Laporan Daftar Alat';

    protected static ?string $navigationIcon = 'heroicon-m-rectangle-stack';

    public static function shouldRegisterNavigation(): bool
    {
        return false; // hanya muncul ketika digunakan
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_barcode')->label('Kode Barcode')->searchable(),
                TextColumn::make('nama_alat')->label('Nama Alat')->searchable(),
                TextColumn::make('kategori_alat')->label('Kategori'),
                TextColumn::make('merek_alat')->label('Merek'),
                TextColumn::make('mobil.nomor_plat')
                    ->label('Lokasi')
                    ->default('Gudang')
                    ->placeholder('Gudang'),
                BadgeColumn::make('status_alat')
                    ->label('Status Alat')
                    ->colors([
                        'success' => 'Bagus',
                        'danger' => 'Rusak',
                        'warning' => 'Hilang',
                    ])
                    ->icons([
                        'heroicon-o-check-circle' => 'Bagus',
                        'heroicon-o-exclamation-triangle' => 'Rusak',
                        'heroicon-o-no-symbol' => 'Hilang',
                    ])
                    ->toggleable(),
                TextColumn::make('tanggal_pembelian')
                    ->label('Tanggal Pembelian')
                    ->date('d M Y'),
            ])
            ->filters([
                MultiSelectFilter::make('status_alat')
                    ->label('Filter Status Alat')
                    ->options([
                        'Bagus' => 'Bagus',
                        'Rusak' => 'Rusak',
                        'Hilang' => 'Hilang',
                    ])
                    ->placeholder('Semua Status')
                    ->searchable()
                    ->default(['Rusak', 'Hilang']),
            ])
            ->defaultSort('nama_alat')
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make()->color('warning'),
                    Action::make('konfirmasi')
                        ->label('Konfirmasi')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->form([
                            TextInput::make('aksi')
                                ->label('Aksi')
                                ->required(),
                            Textarea::make('catatan')
                                ->label('Catatan')
                                ->rows(3)
                                ->required(),
                        ])
                        ->action(function (Model $record, array $data) {
                            Riwayat::create([
                                'riwayatable_id' => $record->id,
                                'riwayatable_type' => get_class($record),
                                'status' => 'Proses',
                                'user_id' => auth()->id(),
                                'tanggal_cek' => now()->toDateString(),
                                'aksi' => $data['aksi'],
                                'catatan' => $data['catatan'],
                            ]);

                            session()->flash('message', 'Laporan Alat berhasil dikonfirmasi!');
                        })
                        ->visible(fn($record) => $record->status_alat !== 'Bagus'),
                ])
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLaporanAlats::route('/'),
            'create' => Pages\CreateLaporanAlat::route('/create'),
            'view' => Pages\ViewLaporanAlat::route('/{record}'),
            'edit' => Pages\EditLaporanAlat::route('/{record}/edit'),
        ];
    }
}
