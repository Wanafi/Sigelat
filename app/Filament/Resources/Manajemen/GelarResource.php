<?php

namespace App\Filament\Resources\Manajemen;

use Filament\Forms;
use App\Models\Alat;
use Filament\Tables;
use App\Models\Gelar;
use App\Models\Mobil;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\CheckboxList;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\Manajemen\GelarResource\Pages;
use App\Filament\Resources\Manajemen\GelarResource\Pages\EditGelar;
use App\Filament\Resources\Manajemen\GelarResource\Pages\ListGelars;
use App\Filament\Resources\Manajemen\GelarResource\Pages\CreateGelar;

class GelarResource extends Resource
{
    protected static ?string $model = Gelar::class;

    protected static ?string $navigationIcon = 'heroicon-m-map';
    protected static ?string $label = 'Kegiatan';
    protected static ?string $navigationGroup = 'Manajemen';
    protected static ?string $pluralLabel = 'Daftar Kegiatan Gelar Alat';
    protected static ?string $navigationLabel = 'Gelar Alat';
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function form(Form $form): Form
    {
        $totalAlat = Alat::count();

        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Select::make('mobil_id')
                            ->label('Mobil')
                            ->options(Mobil::all()->pluck('nomor_plat', 'id'))
                            ->required()
                            ->searchable()
                            ->prefixIcon('heroicon-o-truck')
                            ->preload()
                            ->label('Nomor Plat Mobil')
                            ->placeholder('Belum ditempatkan')
                            ->nullable(),
                        Forms\Components\CheckboxList::make('alat_ids')
                            ->label('Pilih Alat yang Ada')
                            ->options(Alat::all()->pluck('nama_alat', 'id'))
                            ->columns(2)
                            ->dehydrated(false)
                            ->live()
                            ->afterStateUpdated(function (Set $set, ?array $state) use ($totalAlat) {
                                $jumlahAlatTercentang = count($state ?? []);
                                $status = ($jumlahAlatTercentang === $totalAlat) ? 'Lengkap' : 'Tidak Lengkap';
                                $set('status', $status);
                            }),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'Lengkap' => 'Lengkap',
                                'TidakLengkap' => 'TidakLengkap',
                            ])
                            ->default(function (?Gelar $record) {
                                // Cek apakah ada $record, jika ada tentukan default sesuai statusnya
                                if ($record) {
                                    // Mengambil status berdasarkan data alat yang ada di $record
                                    return count($record->alat_ids) == Alat::count() ? 'Lengkap' : 'Tidak Lengkap';
                                }
                                // Defaultkan status 'Tidak Lengkap' saat buat baru
                                return 'TidakLengkap';
                            })
                            ->disabled()
                            ->dehydrated(true),


                        Forms\Components\DatePicker::make('tanggal_cek')
                            ->label('Tanggal Cek')
                            ->default(now())
                            ->required()
                            ->dehydrated(true),
                    ])->columns(2),
            ]);
    }


    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('mobil.nomor_plat')
                    ->label('Mobil'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pelapor')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status')->label('Status')
                    ->badge()
                    ->colors([
                        'success' => 'Lengkap',
                        'warning' => 'TidakLengkap',
                        'gray' => 'Proses',
                    ])
                    ->icons([
                        'heroicon-o-check-circle' => 'Lengkap',
                        'heroicon-o-exclamation-triangle' => 'TidakLengkap',
                        'heroicon-o-clock' => 'Proses',
                    ]),
                Tables\Columns\TextColumn::make('tanggal_cek')
                    ->label('Tanggal Cek')
                    ->date(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->searchable()
                    ->preload()
                    ->label('Filter Status')
                    ->indicator('Filter By')
                    ->options([
                        'Lengkap' => 'Lengkap',
                        'TidakLengkap' => 'TidakLengkap',
                    ]),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make()
                        ->color('warning'),
                    DeleteAction::make()
                        ->color('danger'),
                ])->icon('heroicon-m-ellipsis-horizontal'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
