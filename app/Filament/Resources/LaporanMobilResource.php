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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $navigationLabel = 'Laporan Mobil';
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
                        'warning' => 'TidakAktif',
                        'danger' => 'DalamPerbaikan',
                    ])
                    ->icons([
                        'heroicon-o-check-circle' => 'Aktif',
                        'heroicon-o-exclamation-triangle' => 'TidakAktif',
                        'heroicon-o-wrench-screwdriver' => 'DalamPerbaikan',
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
                    ->default(['Tidak Aktif', 'Dalam Perbaikan']),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
