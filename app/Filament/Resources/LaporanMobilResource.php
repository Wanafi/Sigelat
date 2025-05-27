<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Mobil;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\MultiSelectFilter;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\LaporanMobilResource\Pages;
use App\Filament\Resources\LaporanMobilResource\RelationManagers;
use App\Filament\Resources\RiwayatsRelationManagerResource\RelationManagers\RiwayatsRelationManager;

class LaporanMobilResource extends Resource
{
    protected static ?string $model = Mobil::class;

    protected static ?string $navigationIcon = 'heroicon-m-rectangle-stack';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $navigationLabel = 'Laporkan Mobil';
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }



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
                Tables\Columns\TextColumn::make('nomor_plat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('merk_mobil'),
                Tables\Columns\TextColumn::make('no_unit'),
                BadgeColumn::make('status_mobil')
                    ->label('Status')
                    ->colors([
                        'success' => 'Aktif',
                        'warning' => 'Tidak Aktif',
                        'danger' => 'Dalam Perbaikan',
                    ])
                    ->icons([
                        'heroicon-o-check-circle' => 'Aktif',
                        'heroicon-o-exclamation-triangle' => 'Tidak Aktif',
                        'heroicon-o-wrench-screwdriver' => 'Dalam Perbaikan',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                MultiSelectFilter::make('status_mobil')
                    ->label('Status Mobil')
                    ->options([
                        'aktif' => 'Aktif',
                        'tidakaktif' => 'Tidak Aktif',
                        'dalamperbaikan' => 'Dalam Perbaikan',
                    ])
                    ->default(['TidakAktif', 'DalamPerbaikan']),
            ])
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
                        /** @var Mobil $record */
                        $record->status_mobil = 'ProsesPelaporan';
                        $record->save();
            
                        \App\Models\Riwayat::create([
                            'riwayatable_id' => $record->id,
                            'riwayatable_type' => get_class($record),
                            'status' => 'proses',
                            'user_id' => auth()->id(),
                            'tanggal_cek' => now()->toDateString(),
                            'aksi' => $data['aksi'],
                            'catatan' => $data['catatan'],
                        ]);
            
                        session()->flash('message', 'Laporan Gelar berhasil diproses!');
                    })
                    ->visible(fn ($record) => $record->status !== 'Proses'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListLaporanMobils::route('/'),
            'create' => Pages\CreateLaporanMobil::route('/create'),
            'view' => Pages\ViewLaporanMobil::route('/{record}'),
            'edit' => Pages\EditLaporanMobil::route('/{record}/edit'),
        ];
    }
}
