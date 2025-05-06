<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Alat;
use Filament\Tables;
use App\Models\Laporan;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\MultiSelectFilter;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\LaporanAlatResource\Pages;
use App\Filament\Resources\LaporanAlatResource\RelationManagers;
use App\Filament\Resources\RiwayatsRelationManagerResource\RelationManagers\RiwayatsRelationManager;

class LaporanAlatResource extends Resource
{
    protected static ?string $model = Alat::class;
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $navigationLabel = 'Laporkan Alat';
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }



    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_barcode')
                    ->label('Kode Barcode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_alat')
                    ->label('Nama Alat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kategori_alat')
                    ->label('Kategori'),
                Tables\Columns\TextColumn::make('merek_alat')
                    ->label('Merek'),
                Tables\Columns\TextColumn::make('mobil.nama_mobil')
                    ->label('Lokasi')
                    ->default('Gudang')
                    ->placeholder('Gudang'),
                Tables\Columns\BadgeColumn::make('status_alat')
                    ->label('Status Alat')
                    ->colors([
                        'primary' => 'Dipinjam',
                        'danger' => 'Rusak',
                        'warning' => 'Habis',
                    ])
                    ->toggleable(),
                Tables\Columns\TextColumn::make('tanggal_pembelian')
                    ->label('Tanggal Pembelian')
                    ->date('d M Y'),
            ])
            ->filters([
                MultiSelectFilter::make('status_alat')
                    ->label('Filter Status Alat')
                    ->options([
                        'Dipinjam' => 'Dipinjam',
                        'Rusak' => 'Rusak',
                        'Habis' => 'Habis',
                    ])
                    ->placeholder('Semua Status')
                    ->searchable()
                    ->default(['rusak', 'habis']),
            ])
            ->defaultSort('nama_alat')

            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('konfirmasi')
                    ->label('Konfirmasi')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->form([
                        Forms\Components\TextInput::make('aksi')
                            ->label('Aksi')
                            ->required(),

                        Forms\Components\Textarea::make('catatan')
                            ->label('Catatan')
                            ->rows(3)
                            ->required(),
                    ])
                    ->action(function (Model $record, array $data) {
                        /** @var Alat $record */
                        $record->status_alat = 'proses';
                        $record->save();

                        \App\Models\Riwayat::create([
                            'riwayatable_id' => $record->id,
                            'riwayatable_type' => get_class($record),
                            'status' => 'Proses',
                            'user_id' => auth()->id(),
                            'tanggal_cek' => now()->toDateString(),
                            'aksi' => $data['aksi'],
                            'catatan' => $data['catatan'],
                        ]);

                        session()->flash('message', 'Laporan Alat berhasil diproses!');
                    })
                    ->visible(fn($record) => $record->status !== 'Proses'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
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
