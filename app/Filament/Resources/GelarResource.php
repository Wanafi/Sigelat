<?php

namespace App\Filament\Resources\Manajemen;

use Filament\Forms;
use App\Models\Alat;
use App\Models\Gelar;
use App\Models\Mobil;
use Illuminate\Support\Facades\DB;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\Manajemen\GelarResource\Pages;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section as InfoSection;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;

class GelarResource extends Resource
{
    protected static ?string $model = Gelar::class;

    protected static ?string $navigationIcon = 'heroicon-m-map';
    protected static ?string $label = 'Kegiatan';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = 'Manajemen';
    protected static ?string $pluralLabel = 'Daftar Kegiatan Gelar Alat';
    protected static ?string $navigationLabel = 'Gelar Alat';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Section::make('Informasi Kegiatan')
                    ->schema([
                        Select::make('mobil_id')
                            ->label('Nomor Plat Mobil')
                            ->relationship('mobil', 'nomor_plat')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function (\Filament\Forms\Set $set, $state) {
                                $alatList = \App\Models\Alat::where('mobil_id', $state)
                                    ->get()
                                    ->map(fn($alat) => [
                                        'alat_id' => $alat->id,
                                        'nama_alat' => $alat->nama_alat,
                                        'kondisi' => $alat->status_alat,
                                        'keterangan' => null,
                                    ])
                                    ->toArray();

                                $set('detail_alats', $alatList);
                            }),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'Lengkap' => 'Lengkap',
                                'Tidak Lengkap' => 'Tidak Lengkap',
                            ])
                            ->default('Tidak Lengkap')
                            ->required(),

                        DatePicker::make('tanggal_cek')
                            ->label('Tanggal Cek')
                            ->default(now())
                            ->required(),

                        Select::make('pelaksanas_id')
                            ->label('Petugas Pelaksana')
                            ->multiple()
                            ->options(
                                \App\Models\User::pluck('name', 'id')
                            )
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])->columns(2),

                Section::make('Daftar Alat di Mobil')
                    ->schema([
                        Repeater::make('detail_alats')
                            ->label('Detail Alat')
                            ->schema([
                                TextInput::make('alat_id')->label('ID Alat')->hidden(),
                                TextInput::make('nama_alat')->label('Nama Alat')->disabled(),
                                Select::make('kondisi')
                                    ->label('Kondisi Alat')
                                    ->options([
                                        'Bagus' => 'Bagus',
                                        'Rusak' => 'Rusak',
                                        'Hilang' => 'Hilang',
                                    ])
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $get) {
                                        $alatId = $get('alat_id');
                                        if ($alatId && in_array($state, ['Bagus', 'Rusak', 'Hilang'])) {
                                            \App\Models\Alat::where('id', $alatId)->update(['status_alat' => $state]);
                                        }
                                    }),

                                TextInput::make('keterangan')->label('Keterangan'),
                            ])
                            ->columns(3)
                            ->disableItemCreation()
                            ->disableItemDeletion()
                            ->disableItemMovement()
                            ->visible(fn($get) => filled($get('detail_alats'))),
                    ]),
            ]);
    }


    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                InfoSection::make('Informasi Kegiatan')
                    ->schema([
                        TextEntry::make('mobil.nomor_plat')
                            ->label('Nomor Plat Mobil')
                            ->icon('heroicon-m-truck'),

                        TextEntry::make('tanggal_cek')
                            ->label('Tanggal Cek')
                            ->icon('heroicon-m-calendar-days')
                            ->date(),

                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->colors([
                                'success' => 'Lengkap',
                                'warning' => 'Tidak Lengkap',
                            ])
                            ->icons([
                                'heroicon-o-check-circle' => 'Lengkap',
                                'heroicon-o-exclamation-triangle' => 'Tidak Lengkap',
                            ]),

                        TextEntry::make('pelaksanas')
                            ->label('Pelaksana')
                            ->icon('heroicon-m-user-group')
                            ->state(fn($record) => $record->pelaksanas->pluck('name')->join(', ')),
                    ])->columns(2),

                InfoSection::make('Daftar Alat di Mobil')
                    ->schema([
                        RepeatableEntry::make('detail_alats')
                            ->label('Daftar Alat')
                            ->state(fn($record) => $record->detail_alats()
                                ->with('alat')
                                ->get()
                                ->map(fn($detail) => [
                                    'nama_alat' => $detail->alat->nama_alat ?? '-',
                                    'kode_barcode' => $detail->alat->kode_barcode ?? '-',
                                    'kondisi' => $detail->status_alat,
                                    'keterangan' => $detail->keterangan,
                                ])->toArray())
                            ->schema([
                                TextEntry::make('nama_alat')->label('Nama Alat'),
                                TextEntry::make('kode_barcode')->label('Kode Barcode'),
                                TextEntry::make('kondisi')->label('Kondisi'),
                                TextEntry::make('keterangan')->label('Keterangan')->columnSpanFull(),
                            ])
                            ->columns(2),
                    ]),
            ]);
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        return $data;
    }

    public static function afterCreate(Gelar $record): void
    {
        $alatList = request()->input('data.alat_list', []);
        $pelaksanaIds = request()->input('data.pelaksanas_id', []);
        $statusGelar = 'Lengkap';

        foreach ($alatList as $alat) {
            if (!isset($alat['alat_id'], $alat['kondisi'])) continue;

            // ✅ Update status_alat di tabel alats
            \App\Models\Alat::where('id', $alat['alat_id'])->update([
                'status_alat' => $alat['kondisi'],
                'mobil_id' => $record->mobil_id,
            ]);

            if ($alat['kondisi'] === 'Hilang') {
                $statusGelar = 'Tidak Lengkap';
            }
        }

        if (!empty($pelaksanaIds)) {
            $record->pelaksanas()->sync($pelaksanaIds);
        }

        $record->update(['status' => $statusGelar]);
    }

    public static function afterSave(Gelar $record): void
    {
        // cukup panggil ulang afterCreate agar semua logic tetap berlaku
        self::afterCreate($record);
    }



    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('mobil.nomor_plat')
                    ->label('Nomor Plat'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'success' => 'Lengkap',
                        'warning' => 'Tidak Lengkap',
                        'gray' => 'Proses',
                    ])
                    ->icons([
                        'heroicon-o-check-circle' => 'Lengkap',
                        'heroicon-o-exclamation-triangle' => 'Tidak Lengkap',
                        'heroicon-o-clock' => 'Proses',
                    ]),
                Tables\Columns\TextColumn::make('tanggal_cek')
                    ->label('Tanggal Cek')
                    ->date()
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->searchable()
                    ->preload()
                    ->label('Filter Status')
                    ->indicator('Filter By')
                    ->options([
                        'Lengkap' => 'Lengkap',
                        'Tidak Lengkap' => 'Tidak Lengkap',
                    ]),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make()->color('warning'),
                    DeleteAction::make()->color('danger'),
                ])->icon('heroicon-m-ellipsis-horizontal'),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGelars::route('/'),
            'create' => Pages\CreateGelar::route('/create'),
            'edit' => Pages\EditGelar::route('/{record}/edit'),
        ];
    }
}
