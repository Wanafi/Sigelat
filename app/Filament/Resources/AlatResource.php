<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Alat;
use App\Models\Mobil;
use Filament\Tables;
use BaconQrCode\Writer;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Actions\EditAction;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use BaconQrCode\Renderer\ImageRenderer;
use Filament\Infolists\Components\Grid;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Split;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Forms\Components\ToggleButtons;
use Filament\Infolists\Components\HtmlEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\AlatResource\Pages;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use Filament\Forms\Components\Section as FormSection;
use App\Filament\Resources\AlatResource\Pages\EditAlat;
use App\Filament\Resources\AlatResource\Pages\ViewAlat;
use App\Filament\Resources\AlatResource\Pages\ListAlats;
use App\Filament\Resources\AlatResource\Pages\CreateAlat;
use App\Filament\Resources\AlatResource\RelationManagers;

class AlatResource extends Resource
{
    protected static ?string $model = Alat::class;
    protected static ?string $navigationIcon = 'heroicon-m-wrench-screwdriver';
    protected static ?string $navigationLabel = 'Daftar Alat';
    protected static ?string $modelLabel = 'Daftar Alat';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = 'Manajemen';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FormSection::make('Barcode')
                    ->description('Kode Untuk Generate Barcode')
                    ->schema([
                        TextInput::make('kode_barcode')
                            ->required()
                            ->disabled()
                            ->maxLength(32)
                            ->dehydrated()
                            ->prefixIcon('heroicon-o-qr-code')
                            ->default('QR-' . random_int(100000, 999999))
                    ]),
                FormSection::make('Deskripsi Alat')
                    ->description('Deskripsikan Alat Yang Ingin DiCatat')
                    ->schema([
                        TextInput::make('nama_alat')->required()->maxLength(255),
                        Select::make('kategori_alat')
                            ->label('Kategori Alat')
                            ->required()
                            ->options([
                                'distribusi' => 'Distribusi',
                                'pemeliharaan' => 'Pemeliharaan',
                                'proteksi' => 'Proteksi',
                                'pengukuran' => 'Pengukuran',
                                'energi_terbarukan' => 'Energi Terbarukan',
                                'pendukung' => 'Pendukung',
                            ])
                            ->searchable()
                            ->native(false)
                            ->placeholder('Pilih Kategori'),
                        TextInput::make('merek_alat')->required(),
                        MarkdownEditor::make('spesifikasi')
                            ->placeholder('Tulis spesifikasi alat di sini...')
                            ->columnSpanFull(),
                    ])->columns(3),
                FormSection::make('Tanggal')
                    ->description('Masukan Tanggal Masuk/Keluar Alat')
                    ->schema([
                        DatePicker::make('tanggal_pembelian')
                            ->required()
                            ->displayFormat('d/m/Y'),
                    ]),
                FormSection::make('Status')
                    ->description('Masukan Status Alat')
                    ->schema([
                        ToggleButtons::make('status_alat')
                            ->inline()
                            ->options([
                                'Bagus' => 'Bagus',
                                'Rusak' => 'Rusak',
                                'Hilang' => 'Hilang',
                            ])
                            ->colors([
                                'Bagus' => 'success',
                                'Rusak' => 'warning',
                                'Hilang' => 'danger',
                            ])
                            ->icons([
                                'Bagus' => 'heroicon-o-check-circle',
                                'Rusak' => 'heroicon-o-exclamation-triangle',
                                'Hilang' => 'heroicon-o-wrench-screwdriver',
                            ])
                            ->required(),
                    ]),
                FormSection::make('Mobil')
                    ->description('Pilih Mobil yang pernah memakai alat ini')
                    ->schema([
                        Select::make('mobil_id')
                            ->label('Nomor Plat Mobil')
                            ->relationship('mobil', 'nomor_plat')
                            ->searchable()
                            ->preload()
                            ->placeholder('Belum ditempatkan'),
                    ])
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_barcode')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('nama_alat')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('kategori_alat')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('merek_alat')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('tanggal_pembelian')->date()->sortable(),
                BadgeColumn::make('status_alat')
                    ->label('Status')
                    ->sortable()
                    ->colors([
                        'success' => 'Bagus',
                        'warning' => 'Rusak',
                        'danger' => 'Hilang',
                    ])
                    ->icons([
                        'heroicon-o-check-circle' => 'Bagus',
                        'heroicon-o-exclamation-triangle' => 'Rusak',
                        'heroicon-o-wrench-screwdriver' => 'Hilang',
                    ]),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status_alat')
                    ->label('Filter Status')
                    ->options([
                        'Bagus' => 'Bagus',
                        'Rusak' => 'Rusak',
                        'Hilang' => 'Hilang',
                    ])
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make()->color('warning'),
                    DeleteAction::make()->color('danger'),
                ])->icon('heroicon-m-ellipsis-horizontal'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make()
                    ->schema([
                        Split::make([
                            Grid::make(2)
                                ->schema([
                                    Section::make([
                                        TextEntry::make('kode_barcode')->icon('heroicon-m-qr-code')->copyable(),
                                        TextEntry::make('nama_alat'),
                                        TextEntry::make('status_alat')
                                            ->badge()
                                            ->colors([
                                                'success' => 'Bagus',
                                                'warning' => 'Rusak',
                                                'danger' => 'Hilang',
                                            ])
                                            ->icons([
                                                'heroicon-o-check-circle' => 'Bagus',
                                                'heroicon-o-exclamation-triangle' => 'Rusak',
                                                'heroicon-o-wrench-screwdriver' => 'Hilang',
                                            ]),
                                        TextEntry::make('mobil.nomor_plat')
                                            ->label('Digunakan di Mobil')
                                            ->icon('heroicon-m-truck')
                                            ->visible(fn($record) => $record->mobil !== null),
                                    ])->columns(2),
                                    Section::make([
                                        TextEntry::make('merek_alat'),
                                        TextEntry::make('kategori_alat')->badge(),
                                    ])->columns(2),
                                ]),
                            TextEntry::make('qrcode')
                                ->label('QR Code')
                                ->html()
                                ->state(function ($record) {
                                    // Ganti URL ini ke URL publik (scan)
                                    $url = 'https://sigelat.loca.lt/scan/' . $record->kode_barcode;

                                    $renderer = new \BaconQrCode\Renderer\ImageRenderer(
                                        new \BaconQrCode\Renderer\RendererStyle\RendererStyle(200),
                                        new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
                                    );

                                    $writer = new \BaconQrCode\Writer($renderer);
                                    $qrSvg = $writer->writeString($url);
                                    $base64 = base64_encode($qrSvg);

                                    return '<img src="data:image/svg+xml;base64,' . $base64 . '" width="300" height="300">';
                                })
                                ->hiddenLabel(),


                        ])
                    ]),
                Section::make('Deskripsi Alat')
                    ->schema([
                        TextEntry::make('spesifikasi')->prose()->markdown()->hiddenLabel(),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAlats::route('/'),
            'create' => CreateAlat::route('/create'),
            'view' => ViewAlat::route('/{record}'),
            'edit' => EditAlat::route('/{record}/edit'),
        ];
    }

    public static function getLabel(): string
    {
        return 'Daftar Alat';
    }

    public static function getPluralLabel(): string
    {
        return 'Daftar Alat';
    }
}
