<?php

namespace App\Filament\Resources;

use App\Models\Riwayat;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\ViewAction;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\RiwayatResource\Pages;

class RiwayatResource extends Resource
{
    protected static ?string $model = Riwayat::class;

    protected static ?string $navigationIcon = 'heroicon-m-clock';
    protected static ?int $navigationSort = 1;
    protected static ?string $modelLabel = 'Riwayat';
    protected static ?string $navigationLabel = 'Riwayat Konfirmasi';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pelapor')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('tanggal_cek')
                    ->label('Tanggal Cek')
                    ->date()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\BadgeColumn::make('riwayatable_type')
                    ->label('Jenis Laporan')
                    ->getStateUsing(function ($record) {
                        return match ($record->riwayatable_type) {
                            'App\\Models\\Mobil' => 'Mobil',
                            'App\\Models\\Alat' => 'Alat',
                            'App\\Models\\Gelar' => 'Gelar',
                            default => 'Lainnya',
                        };
                    })
                    ->colors([
                        'info' => 'Mobil',
                        'success' => 'Alat',
                        'warning' => 'Gelar',
                    ])
                    ->icons([
                        'heroicon-o-truck' => 'Mobil',
                        'heroicon-o-wrench' => 'Alat',
                        'heroicon-o-map' => 'Gelar',
                    ])
                    ->toggleable(),

                Tables\Columns\TextColumn::make('aks')
                    ->label('Aksi')
                    ->getStateUsing(fn($record) => $record->aksi ?: '-')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('catatan')
                    ->label('Catatan')
                    ->default('-')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'gray' => 'Proses',
                        'success' => 'Selesai',
                    ])
                    ->icons([
                        'heroicon-o-clock' => 'Proses',
                        'heroicon-o-check-circle' => 'Selesai',
                    ])
                    ->sortable()
                    ->toggleable(),
            ])
            ->actions([
                ViewAction::make()
                    ->infolist([
                        Section::make('Detail Riwayat')
                            ->schema([
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
                                    }),

                                TextEntry::make('tanggal_cek')
                                    ->label('Tanggal Cek')
                                    ->date()
                                    ->icon('heroicon-o-calendar'),

                                TextEntry::make('aksi')
                                    ->label('Aksi')
                                    ->default('-'),

                                TextEntry::make('catatan')
                                    ->label('Catatan')
                                    ->default('-'),

                                TextEntry::make('status')
                                    ->label('Status')
                                    ->badge()
                                    ->icon(fn($state) => $state === 'Selesai' ? 'heroicon-o-check-circle' : 'heroicon-o-clock')
                                    ->color(fn($state) => $state === 'Selesai' ? 'success' : 'gray'),
                            ])
                            ->columns(2),
                    ]),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRiwayats::route('/'),
        ];
    }

    public static function getLabel(): string
    {
        return 'Riwayat / Aktifitas';
    }

    public static function getPluralLabel(): string
    {
        return 'Riwayat';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
