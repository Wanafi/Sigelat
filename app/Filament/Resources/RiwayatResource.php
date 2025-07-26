<?php

namespace App\Filament\Resources;

use App\Models\Riwayat;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\RiwayatResource\Pages;

class RiwayatResource extends Resource
{
    protected static ?string $model = Riwayat::class;

    protected static ?string $navigationIcon = 'heroicon-m-clock';
    protected static ?int $navigationSort = 4;
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
                    ->getStateUsing(fn($record) => match ($record->riwayatable_type) {
                        'App\\Models\\Mobil' => 'Mobil',
                        'App\\Models\\Alat' => 'Alat',
                        'App\\Models\\Gelar' => 'Gelar',
                        default => 'Lainnya',
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

                Tables\Columns\TextColumn::make('aksi')
                    ->label('Aksi')
                    ->default('-')
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
                ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function infolist(\Filament\Infolists\Infolist $infolist): \Filament\Infolists\Infolist
    {
        return $infolist
            ->schema([
                Section::make('Detail Riwayat')
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('User')
                            ->extraAttributes([
                                'class' => 'text-gray-800 bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 text-sm font-medium',
                            ]),

                        TextEntry::make('riwayatable_type')
                            ->label('Jenis Laporan')
                            ->formatStateUsing(fn($state) => match ($state) {
                                'App\\Models\\Mobil' => 'Mobil',
                                'App\\Models\\Alat' => 'Alat',
                                'App\\Models\\Gelar' => 'Gelar',
                                default => 'Lainnya',
                            })
                            ->badge()
                            ->color(fn($state) => match ($state) {
                                'App\\Models\\Mobil' => 'info',
                                'App\\Models\\Alat' => 'success',
                                'App\\Models\\Gelar' => 'warning',
                                default => 'gray',
                            })
                            ->icon(fn($state) => match ($state) {
                                'App\\Models\\Mobil' => 'heroicon-o-truck',
                                'App\\Models\\Alat' => 'heroicon-o-wrench',
                                'App\\Models\\Gelar' => 'heroicon-o-map',
                                default => 'heroicon-o-question-mark-circle',
                            }),

                        TextEntry::make('tanggal_cek')
                            ->label('Tanggal Cek')
                            ->date()
                            ->icon('heroicon-o-calendar')
                            ->extraAttributes([
                                'class' => 'text-gray-800 bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 text-sm font-medium',
                            ]),

                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->icon(fn($state) => $state === 'Selesai' ? 'heroicon-o-check-circle' : 'heroicon-o-clock')
                            ->color(fn($state) => $state === 'Selesai' ? 'success' : 'gray'),
                    ])
                    ->columns(2), // tampil 2 kolom

                Section::make('Rincian Tindakan')
                    ->schema([
                        TextEntry::make('aksi')
                            ->label('Aksi yang Dilakukan')
                            ->default('-')
                            ->extraAttributes([
                                'class' => 'text-gray-800 bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 text-sm font-medium',
                            ]),

                        TextEntry::make('catatan')
                            ->label('Catatan / Keterangan')
                            ->default('-')
                            ->extraAttributes([
                                'class' => 'text-gray-800 bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 text-sm font-medium',
                                'style' => 'min-height: 100px;',
                            ]),
                    ])
                    ->columns(1),

                Section::make('Data Terkait')
                    ->schema([
                        TextEntry::make('riwayatable_id')
                            ->label('Lihat Data')
                            ->url(fn($record) => match ($record->riwayatable_type) {
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
                            ]))
                            ->extraAttributes([
                                'class' => 'text-gray-800 bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 text-sm font-medium',
                            ]),
                    ])
                    ->columns(1),
            ]);
    }




    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRiwayats::route('/'),
            'view' => Pages\ViewRiwayat::route('/{record}'),
        ];
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
