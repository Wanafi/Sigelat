<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Mobil;
use Filament\Forms\Form;

use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\ToggleButtons;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\Actions\Action;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section as InfoSection;
use App\Filament\Resources\MobilResource\Pages\EditMobil;
use App\Filament\Resources\MobilResource\Pages\ViewMobil;
use App\Filament\Resources\MobilResource\Pages\ListMobils;
use App\Filament\Resources\MobilResource\Pages\CreateMobil;

class MobilResource extends Resource
{
    protected static ?string $model = Mobil::class;
    protected static ?string $navigationIcon = 'heroicon-m-truck';
    protected static ?string $navigationLabel = 'Daftar Mobil';
    protected static ?string $modelLabel = 'Daftar Mobil';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = 'Manajemen';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Informasi Umum Mobil')
                ->description('Masukkan data mobil dinas operasional secara lengkap.')
                ->schema([

                    Forms\Components\Grid::make()
                        ->columns([
                            'default' => 12,
                            'sm' => 12,
                            'md' => 12,
                            'lg' => 12,
                            'xl' => 12,
                            '2xl' => 12,
                        ])
                        ->schema([

                            // Baris 1
                            TextInput::make('nomor_plat')
                                ->label('Nomor Plat Mobil')
                                ->placeholder('Contoh: DA 1234 XX')
                                ->required()
                                ->columnSpan(4)
                                ->prefixIcon('heroicon-o-identification'),

                            Select::make('nama_tim')
                                ->label('Tim Armada')
                                ->required()
                                ->searchable()
                                ->prefixIcon('heroicon-o-users')
                                ->options([
                                    'Ops' => 'Ops',
                                    'Har' => 'Har',
                                    'Assessment' => 'Assessment',
                                    'Raw' => 'Raw',
                                ])
                                ->placeholder('Pilih Tim')
                                ->native(false)
                                ->columnSpan(4),

                            ToggleButtons::make('status_mobil')
                                ->label('Status Operasional')
                                ->options([
                                    'Aktif' => 'Aktif',
                                    'Tidak Aktif' => 'Tidak Aktif',
                                    'Dalam Perbaikan' => 'Dalam Perbaikan',
                                ])
                                ->icons([
                                    'Aktif' => 'heroicon-o-check-circle',
                                    'Tidak Aktif' => 'heroicon-o-x-circle',
                                    'Dalam Perbaikan' => 'heroicon-o-wrench-screwdriver',
                                ])
                                ->colors([
                                    'Aktif' => 'success',
                                    'Tidak Aktif' => 'warning',
                                    'Dalam Perbaikan' => 'danger',
                                ])
                                ->inline()
                                ->required()
                                ->columnSpan(4),

                            TextInput::make('no_seri')
                                ->label('Nomor Seri Mobil')
                                ->placeholder('Masukkan Nomor Seri')
                                ->columnSpan(4)
                                ->required()
                                ->prefixIcon('heroicon-o-key'),


                            // Baris 2
                            Select::make('merk_mobil')
                                ->label('Merk Mobil')
                                ->options(Mobil::distinct()->pluck('merk_mobil', 'merk_mobil'))
                                ->searchable()
                                ->placeholder('Pilih atau Tambahkan Merek')
                                ->prefixIcon('heroicon-o-truck')
                                ->required()
                                ->columnSpan(4)
                                ->createOptionForm([
                                    TextInput::make('merk_mobil')
                                        ->label('Merek Mobil Baru')
                                        ->required(),
                                ])
                                ->createOptionAction(fn(Action $action) => $action
                                    ->modalHeading('Tambah Merk Mobil')
                                    ->modalSubmitActionLabel('Simpan')
                                    ->modalWidth('md'))
                                ->createOptionUsing(fn(array $data) => $data['merk_mobil']),

                            Select::make('no_unit')
                                ->label('Nomor Unit Mobil')
                                ->options(Mobil::distinct()->pluck('no_unit', 'no_unit'))
                                ->searchable()
                                ->placeholder('Pilih atau Tambahkan No. Unit')
                                ->prefixIcon('heroicon-o-hashtag')
                                ->required()
                                ->columnSpan(3)
                                ->createOptionForm([
                                    TextInput::make('no_unit')
                                        ->label('Nomor Unit Baru')
                                        ->required(),
                                ])
                                ->createOptionAction(fn(Action $action) => $action
                                    ->modalHeading('Tambah Nomor Unit')
                                    ->modalSubmitActionLabel('Simpan')
                                    ->modalWidth('md'))
                                ->createOptionUsing(fn(array $data) => $data['no_unit']),

                        ]),
                ])
                ->columns(1)
                ->columnSpanFull(),
        ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nomor_plat')->searchable(),
                TextColumn::make('nama_tim')
                    ->label('Tim Armada')
                    ->badge()
                    ->colors([
                        'info' => 'Ops',
                        'success' => 'Har',
                        'warning' => 'Assessment',
                        'danger' => 'Raw',
                    ])
                    ->icons([
                        'heroicon-o-rocket-launch' => 'Ops',
                        'heroicon-o-cog-6-tooth' => 'Har',
                        'heroicon-o-clipboard-document-check' => 'Assessment',
                        'heroicon-o-beaker' => 'Raw',
                    ]),
                TextColumn::make('merk_mobil')->label('Merek Mobil'),
                TextColumn::make('no_seri')->label('Nomor Seri')->searchable(),
                TextColumn::make('no_unit'),
                BadgeColumn::make('status_mobil')
                    ->label('Status')
                    ->colors([
                        'success' => 'Aktif',
                        'danger' => 'Tidak Aktif',
                        'warning' => 'Dalam Perbaikan',
                    ])
                    ->icons([
                        'heroicon-o-check-circle' => 'Aktif',
                        'heroicon-o-exclamation-triangle' => 'Tidak Aktif',
                        'heroicon-o-wrench-screwdriver' => 'Dalam Perbaikan',
                    ]),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('no_unit')
                    ->searchable()
                    ->preload()
                    ->label('Filter Unit')
                    ->indicator('Filter By')
                    ->options([
                        'Unit12' => 'Unit 12',
                        'Unit13' => 'Unit 13',
                        'Unit14' => 'Unit 14',
                    ]),
                SelectFilter::make('merk_mobil')
                    ->searchable()
                    ->preload()
                    ->label('Filter Merek')
                    ->indicator('Filter By')
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make()->color('warning'),
                    DeleteAction::make()->color('danger'),
                ])->icon('heroicon-m-ellipsis-horizontal'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                InfoSection::make('📝 Informasi Umum Mobil')
                    ->description('Berisi identitas utama dari kendaraan operasional.')
                    ->schema([
                        TextEntry::make('nomor_plat')
                            ->label('Nomor Plat')
                            ->icon('heroicon-m-identification')
                            ->copyable(),

                        TextEntry::make('merk_mobil')
                            ->label('Merek Mobil')
                            ->icon('heroicon-m-truck')
                            ->copyable(),

                        TextEntry::make('no_unit')
                            ->label('Nomor Unit')
                            ->icon('heroicon-m-hashtag')
                            ->copyable(),

                        TextEntry::make('no_seri')
                            ->label('Nomor Seri')
                            ->icon('heroicon-m-key')
                            ->copyable(),


                        TextEntry::make('nama_tim')
                            ->label('Tim Armada')
                            ->icon('heroicon-m-user-group')
                            ->badge(),

                        TextEntry::make('status_mobil')
                            ->label('Status Operasional')
                            ->badge()
                            ->icon('heroicon-m-shield-check')
                            ->colors([
                                'success' => 'Aktif',
                                'danger' => 'Tidak Aktif',
                                'warning' => 'Dalam Perbaikan',
                            ])
                            ->icons([
                                'Aktif' => 'heroicon-o-check-circle',
                                'Tidak Aktif' => 'heroicon-o-exclamation-triangle',
                                'Dalam Perbaikan' => 'heroicon-o-wrench-screwdriver',
                            ])
                            ->copyable(),
                    ])
                    ->columns([
                        'md' => 2,
                        'lg' => 3,
                    ]),

                InfoSection::make('🧰 Daftar Alat di Kendaraan')
                    ->description('Data seluruh alat yang terdaftar di mobil ini.')
                    ->schema([
                        RepeatableEntry::make('alats')
                            ->label('Alat Terdaftar')
                            ->schema([
                                TextEntry::make('nama_alat')
                                    ->label('Nama Alat')
                                    ->icon('heroicon-m-wrench-screwdriver'),

                                TextEntry::make('kode_barcode')
                                    ->label('Kode Barcode')
                                    ->icon('heroicon-m-qr-code'),

                                TextEntry::make('status_alat')
                                    ->label('Status')
                                    ->badge()
                                    ->colors([
                                        'primary' => 'Bagus',
                                        'warning' => 'Hilang',
                                        'danger' => 'Rusak',
                                    ])
                                    ->icons([
                                        'Bagus' => 'heroicon-o-check-circle',
                                        'Hilang' => 'heroicon-o-no-symbol',
                                        'Rusak' => 'heroicon-o-exclamation-circle',
                                    ]),
                            ])
                            ->columns([
                                'md' => 3,
                                'lg' => 3,
                            ]),
                    ]),
            ]);
    }



    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMobils::route('/'),
            'create' => CreateMobil::route('/create'),
            'view' => ViewMobil::route('/{record}'),
            'edit' => EditMobil::route('/{record}/edit'),
        ];
    }

        public static function getLabel(): string
    {
        return 'Daftar Mobil';
    }

    public static function getPluralLabel(): string
    {
        return 'Daftar Mobil';
    }
}
