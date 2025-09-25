<?php

namespace App\Filament\Resources;

use App\Models\Mobil;
use App\Models\Riwayat;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Notifications\Notification;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Actions\Action;
use App\Filament\Resources\LaporanMobilResource\Pages;

class LaporanMobilResource extends Resource
{
    protected static ?string $model = Mobil::class;
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $navigationLabel = 'Laporan Mobil';
    protected static ?string $modelLabel = 'Laporan Mobil';
    protected static ?string $navigationIcon = 'heroicon-m-rectangle-stack';

    public static function canCreate(): bool
    {
        return false;
    }
    public static function canEdit(Model $record): bool
    {
        return false;
    }
    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_plat')->label('Nomor Plat')->searchable(),
                Tables\Columns\TextColumn::make('merk_mobil')->label('Merek Mobil')->searchable(),
                Tables\Columns\TextColumn::make('no_unit')->label('No Unit'),
                Tables\Columns\BadgeColumn::make('status_mobil')
                    ->label('Status')
                    ->colors([
                        'success' => 'Aktif',
                        'warning' => 'Dalam Perbaikan',
                        'danger' => 'Tidak Aktif',
                    ])
                    ->icons([
                        'Aktif' => 'heroicon-o-check-circle',
                        'heroicon-o-exclamation-triangle' => 'Tidak Aktif',
                        'heroicon-o-wrench-screwdriver' => 'Dalam Perbaikan',
                    ]),
            ])
            ->filters([
                Tables\Filters\MultiSelectFilter::make('status_mobil')
                    ->options([
                        'Aktif' => 'Aktif',
                        'Tidak Aktif' => 'Tidak Aktif',
                        'Dalam Perbaikan' => 'Dalam Perbaikan',
                    ])
                    ->default(['Tidak Aktif', 'Dalam Perbaikan']),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Informasi Mobil')
                    ->schema([
                        TextEntry::make('nomor_plat')
                            ->label('Nomor Plat')
                            ->extraAttributes([
                                'class' => 'px-3 py-2 rounded-xl bg-white/30 backdrop-blur-md border border-white/20 
                shadow-lg text-gray-900',
                                'style' => 'transform: perspective(800px) translateZ(10px);',
                            ]),
                        TextEntry::make('nama_tim')
                            ->label('Tim Armada')
                            ->icon('heroicon-m-user-group')
                            ->badge()
                            ->extraAttributes([
                                'class' => 'px-3 py-2 rounded-xl bg-white/30 backdrop-blur-md border border-white/20 
                shadow-lg text-gray-900',
                                'style' => 'transform: perspective(800px) translateZ(10px);',
                            ]),
                        TextEntry::make('status_mobil')
                            ->label('Status')
                            ->badge()
                            ->color(fn($state) => match ($state) {
                                'Aktif' => 'success',
                                'Tidak Aktif' => 'danger',
                                'Dalam Perbaikan' => 'warning',
                                default => 'gray',
                            })
                            ->extraAttributes([
                                'class' => 'px-3 py-2 rounded-xl bg-white/30 backdrop-blur-md border border-white/20 
                shadow-lg text-gray-900',
                                'style' => 'transform: perspective(800px) translateZ(10px);',
                            ]),
                        TextEntry::make('no_seri')
                            ->label('Nomor Seri')
                            ->icon('heroicon-m-key')
                            ->copyable()
                            ->default('-')
                            ->extraAttributes([
                                'class' => 'px-3 py-2 rounded-xl bg-white/30 backdrop-blur-md border border-white/20 
                shadow-lg text-gray-900',
                                'style' => 'transform: perspective(800px) translateZ(10px);',
                            ]),
                        TextEntry::make('merk_mobil')
                            ->label('Merek')
                            ->extraAttributes([
                                'class' => 'px-3 py-2 rounded-xl bg-white/30 backdrop-blur-md border border-white/20 
                shadow-lg text-gray-900',
                                'style' => 'transform: perspective(800px) translateZ(10px);',
                            ]),
                        TextEntry::make('no_unit')
                            ->label('Nomor Unit')
                            ->extraAttributes([
                                'class' => 'px-3 py-2 rounded-xl bg-white/30 backdrop-blur-md border border-white/20 
                shadow-lg text-gray-900',
                                'style' => 'transform: perspective(800px) translateZ(10px);',
                            ]),

                    ])
                    ->columns(2),

                Actions::make([
                    Action::make('konfirmasi')
                        ->label('Konfirmasi')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->form([
                            TextInput::make('aksi')
                                ->label('Tindakan')
                                ->required(),
                            Textarea::make('catatan')
                                ->label('Catatan')
                                ->required()
                                ->rows(3),
                        ])
                        ->action(function (Model $record, array $data) {
                            $record->update(['status_mobil' => 'Aktif']);

                            Riwayat::create([
                                'riwayatable_id' => $record->id,
                                'riwayatable_type' => get_class($record),
                                'user_id' => auth()->id(),
                                'status' => 'Selesai',
                                'tanggal_cek' => now(),
                                'aksi' => $data['aksi'],
                                'catatan' => $data['catatan'],
                            ]);

                            Notification::make()
                                ->title('Berhasil')
                                ->body('Status mobil berhasil dikonfirmasi & diperbarui.')
                                ->success()
                                ->send();
                        }),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLaporanMobils::route('/'),
            'view' => Pages\ViewLaporanMobil::route('/{record}'),
        ];
    }
    public static function getPluralLabel(): string
    {
        return 'Laporan Mobil';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
