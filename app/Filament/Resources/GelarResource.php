<?php

namespace App\Filament\Resources\Manajemen;

use App\Models\Gelar;
use App\Models\Mobil;
use App\Models\Alat;
use App\Models\User;
use App\Models\Pelaksana;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use App\Filament\Resources\Manajemen\GelarResource\Pages;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section as InfoSection;

class GelarResource extends Resource
{
    protected static ?string $model = Gelar::class;

    protected static ?string $navigationIcon = 'heroicon-m-map';
    protected static ?string $navigationLabel = 'Gelar Alat';
    protected static ?string $pluralLabel = 'Daftar Kegiatan Gelar Alat';
    protected static ?string $navigationGroup = 'Manajemen';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
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
                            $alatList = Alat::where('mobil_id', $state)
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

                    Select::make('pelaksana_ids')
                        ->label('Petugas Pelaksana')
                        ->multiple()
                        ->options(User::pluck('name', 'id'))
                        ->searchable()
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
                                        Alat::where('id', $alatId)->update(['status_alat' => $state]);
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

    public static function afterCreate(Gelar $record): void
    {
        $alatList = request()->input('data.detail_alats', []);
        $pelaksanaIds = request()->input('data.pelaksana_ids', []);
        $statusGelar = 'Lengkap';

        foreach ($alatList as $alat) {
            if (!isset($alat['alat_id'], $alat['kondisi'])) continue;

            Alat::where('id', $alat['alat_id'])->update([
                'status_alat' => $alat['kondisi'],
                'mobil_id' => $record->mobil_id,
            ]);

            if ($alat['kondisi'] === 'Hilang') {
                $statusGelar = 'Tidak Lengkap';
            }
        }

        $record->update(['status' => $statusGelar]);

        if (!empty($pelaksanaIds)) {
            foreach ($pelaksanaIds as $userId) {
                Pelaksana::create([
                    'gelar_id' => $record->id,
                    'user_id' => $userId,
                ]);
            }
        }
    }

    public static function afterSave(Gelar $record): void
    {
        Pelaksana::where('gelar_id', $record->id)->delete();
        self::afterCreate($record);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                InfoSection::make('Informasi Kegiatan')
                    ->schema([
                        TextEntry::make('mobil.nomor_plat')->label('Nomor Plat Mobil')->icon('heroicon-m-truck'),
                        TextEntry::make('tanggal_cek')->label('Tanggal Cek')->icon('heroicon-m-calendar-days')->date(),
                        TextEntry::make('status')->label('Status')->badge()->colors([
                            'success' => 'Lengkap',
                            'warning' => 'Tidak Lengkap',
                        ]),
                        TextEntry::make('pelaksanas')
                            ->label('Pelaksana')
                            ->icon('heroicon-m-user-group')
                            ->state(fn($record) => $record->pelaksanas->map(fn($p) => $p->user->name)->join(', ')),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('mobil.nomor_plat')->label('Nomor Plat'),
                Tables\Columns\TextColumn::make('status')->label('Status')->badge(),
                Tables\Columns\TextColumn::make('tanggal_cek')->label('Tanggal Cek')->date(),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make()->color('warning'),
                    DeleteAction::make()->color('danger'),
                ])
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGelars::route('/'),
            'create' => Pages\CreateGelar::route('/create'),
            'edit' => Pages\EditGelar::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
