<?php

namespace App\Filament\Resources\Manajemen;

use Filament\Forms;
use App\Models\Alat;
use App\Models\Gelar;
use App\Models\Mobil;
use App\Models\User;
use Filament\Forms\Set;
use Filament\Forms\Form;
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
use App\Filament\Resources\Manajemen\GelarResource\Pages\EditGelar;
use App\Filament\Resources\Manajemen\GelarResource\Pages\ListGelars;
use App\Filament\Resources\Manajemen\GelarResource\Pages\CreateGelar;
use Illuminate\Support\Facades\DB;

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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Kegiatan')
                    ->schema([
                        Select::make('mobil_id')
                            ->label('Nomor Plat Mobil')
                            ->options(Mobil::all()->pluck('nomor_plat', 'id'))
                            ->required()
                            ->searchable()
                            ->prefixIcon('heroicon-o-truck')
                            ->preload()
                            ->placeholder('Pilih Mobil')
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state) {
                                $alatList = DB::table('detail_alats')
                                    ->join('alats', 'detail_alats.alat_id', '=', 'alats.id')
                                    ->where('detail_alats.mobil_id', $state)
                                    ->select('alats.id as alat_id', 'alats.nama_alat', 'alats.status_alat')
                                    ->get()
                                    ->map(fn($alat) => [
                                        'alat_id' => $alat->alat_id,
                                        'nama_alat' => $alat->nama_alat,
                                        'kondisi' => $alat->status_alat ?? 'Bagus',
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
                            ->required()
                            ->reactive(),

                        DatePicker::make('tanggal_cek')
                            ->label('Tanggal Cek')
                            ->default(now())
                            ->required(),

                        Select::make('pelaksanas_id')
                            ->label('Petugas Pelaksana')
                            ->multiple()
                            ->relationship('pelaksanas', 'name')
                            ->preload()
                            ->searchable()
                            ->required(),
                    ])->columns(2),

                Section::make('Detail Alat di Mobil')
                    ->schema([
                        Repeater::make('detail_alats')
                            ->label('Detail Alat')
                            ->schema([
                                TextInput::make('alat_id')->label('ID Alat')->hidden(),
                                TextInput::make('nama_alat')
                                    ->label('Nama Alat')
                                    ->disabled(),
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

                                TextInput::make('keterangan')
                                    ->label('Keterangan')
                                    ->nullable(),
                            ])
                            ->columns(3)
                            ->disableItemCreation()
                            ->disableItemDeletion()
                            ->disableItemMovement()
                            ->visible(fn($get) => filled($get('detail_alats'))),
                    ])->columns(2),
            ]);
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        return $data;
    }

    public static function afterCreate(Gelar $record): void
    {
        $alatDetail = request()->input('data.detail_alats', []);
        $pelaksanaIds = request()->input('data.pelaksanas_id', []);

        $statusGelar = 'Lengkap';

        foreach ($alatDetail as $alat) {
            if (!isset($alat['alat_id'], $alat['kondisi'])) {
                continue;
            }

            DB::table('detail_alats')->insert([
                'mobil_id' => $record->mobil_id,
                'alat_id' => $alat['alat_id'],
                'kondisi' => $alat['kondisi'],
                'keterangan' => $alat['keterangan'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Alat::where('id', $alat['alat_id'])->update([
                'status_alat' => $alat['kondisi'],
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
        DB::table('detail_alats')->where('mobil_id', $record->mobil_id)->delete();
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
                        'TidakLengkap' => 'Tidak Lengkap',
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

    protected function getHeaderWidgets(): array
    {
        return [];
    }
}
