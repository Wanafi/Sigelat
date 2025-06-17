<?php

namespace App\Filament\Resources\Manajemen;


use App\Filament\Resources\Manajemen\MobilResource\Pages\EditMobil;
use App\Filament\Resources\Manajemen\MobilResource\Pages\ViewMobil;
use App\Filament\Resources\Manajemen\MobilResource\Pages\ListMobils;
use App\Filament\Resources\Manajemen\MobilResource\Pages\CreateMobil;


use Filament\Forms;
use Filament\Tables;
use App\Models\Mobil;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
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
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\MobilResource\Pages;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section as infosection;

class MobilResource extends Resource
{
    protected static ?string $model = Mobil::class;
    protected static ?string $navigationIcon = 'heroicon-m-truck';
    protected static ?string $navigationLabel = 'Daftar Mobil';
    protected static ?string $modelLabel = 'Car List';
    protected static ?string $navigationGroup = 'Manajemen';
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Informasi Mobil')
                ->description('Lengkapi detail mobil dengan benar.')
                ->schema([

                    TextInput::make('nomor_plat')
                        ->label('Nomor Plat')
                        ->required()
                        ->placeholder('Contoh: DA 1234 XX')
                        ->prefixIcon('heroicon-o-identification'),

                    Select::make('nama_tim')
                        ->label('Nama Tim Armada')
                        ->options([
                            'Ops' => 'Ops',
                            'Har' => 'Har',
                            'Assessment' => 'Assessment',
                            'Raw' => 'Raw',
                        ])
                        ->required()
                        ->searchable()
                        ->native(false) // agar tampil lebih menarik
                        ->columnSpanFull(),

                    Select::make('merk_mobil')
                        ->label('Merk Mobil')
                        ->prefixIcon('heroicon-o-truck')
                        ->required()
                        ->options([
                            'Hilux' => 'Hilux',
                            'Innova' => 'Innova',
                            'Carry' => 'Carry',
                        ])
                        ->searchable()
                        ->placeholder('Pilih Merk Mobil'),

                    Select::make('no_unit')
                        ->label('Nomor Unit')
                        ->required()
                        ->prefixIcon('heroicon-o-numbered-list')
                        ->options([
                            'Unit12' => 'Unit 12',
                            'Unit13' => 'Unit 13',
                            'Unit14' => 'Unit 14',
                        ])
                        ->placeholder('Pilih Nomor Unit'),

                    Select::make('status_mobil')
                        ->label('Status Mobil')
                        ->required()
                        ->prefixIcon('heroicon-o-truck')
                        ->options([
                            'Aktif' => 'Aktif',
                            'Tidak Aktif' => 'Tidak Aktif',
                            'DalamPerbaikan' => 'Dalam Perbaikan',
                        ])
                        ->placeholder('Pilih Status'),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_plat')
                    ->searchable(),
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
                    ->options([
                        'Hilux' => 'Hilux',
                        'Innova' => 'Innova',
                        'Carry' => 'Carry',
                    ])
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
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                infoSection::make('Informasi Mobil')
                    ->schema([
                        TextEntry::make('nomor_plat')->label('Nomor Plat')->icon('heroicon-m-identification')->copyable(),
                        TextEntry::make('nama_tim')
                            ->label('Tim Armada')
                            ->badge()
                            ->icon('heroicon-m-users'),

                        TextEntry::make('status_mobil')->label('Status Mobil')->badge()->icon('heroicon-m-truck')->copyable(),
                        TextEntry::make('no_unit')->label('No. Unit')->badge()->icon('heroicon-m-truck')->copyable(),
                        TextEntry::make('merk_mobil')->label('Merk Mobil')->badge()->icon('heroicon-m-truck')->copyable(),
                    ])
                    ->columns(2),

                infoSection::make('Daftar Alat di Mobil')
                    ->schema([
                        RepeatableEntry::make('alats')
                            ->schema([
                                TextEntry::make('nama_alat')->label('Nama Alat')->icon('heroicon-m-wrench-screwdriver'),
                                TextEntry::make('kode_barcode')->label('Kode Alat')->icon('heroicon-m-qr-code'),
                            ])
                            ->columns(2),
                    ])
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
            'index' => ListMobils::route('/'),
            'create' => CreateMobil::route('/create'),
            'view' => ViewMobil::route('/{record}'),
            'edit' => EditMobil::route('/{record}/edit'),
        ];
    }
}
