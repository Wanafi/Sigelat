<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Alat;
use Filament\Tables;
use App\Models\Mobil;
use BaconQrCode\Writer;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use BaconQrCode\Renderer\ImageRenderer;
use Filament\Infolists\Components\Grid;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Split;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Section;
use Filament\Forms\Components\ToggleButtons;
use Filament\Infolists\Components\HtmlEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Infolists\Components\ImageEntry;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\AlatResource\Pages;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\RepeatableEntry;
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
    protected static ?string $navigationLabel = 'Alat';
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
                        FileUpload::make('foto')
                            ->label('Foto Dokumentasi')
                            ->image()
                            ->directory('foto-alat')
                            ->imagePreviewHeight('200')
                            ->maxSize(2048)
                            ->columnSpanFull(),
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
                                'Baik' => 'Baik',
                                'Rusak' => 'Rusak',
                                'Hilang' => 'Hilang',
                            ])
                            ->colors([
                                'Baik' => 'success',
                                'Rusak' => 'warning',
                                'Hilang' => 'danger',
                            ])
                            ->icons([
                                'Baik' => 'heroicon-o-check-circle',
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
                ImageColumn::make('foto')
                    ->label('Foto')
                    ->circular()
                    ->height(50)
                    ->width(50)
                    ->url(fn($record) => $record->foto ? asset('storage/' . $record->foto) : null),
                Tables\Columns\TextColumn::make('kode_barcode')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('nama_alat')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('kategori_alat')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('merek_alat')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('tanggal_pembelian')->date()->sortable(),
                BadgeColumn::make('status_alat')
                    ->label('Status')
                    ->sortable()
                    ->colors([
                        'success' => 'Baik',
                        'danger' => 'Rusak',
                        'warning' => 'Hilang',
                    ])
                    ->icons([
                        'heroicon-o-check-circle' => 'Baik',
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
                        'Baik' => 'Baik',
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
                Section::make('Identitas Alat')
                    ->description('Informasi utama mengenai alat inventaris.')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                ImageEntry::make('foto')
                                    ->label('Foto Alat')
                                    ->url(fn($record) => $record->foto ? asset('storage/' . $record->foto) : null)
                                    ->extraAttributes(['class' => 'rounded-xl w-full h-auto object-cover'])
                                    ->columnSpan(1),

                                Grid::make(1)
                                    ->schema([
                                        TextEntry::make('kode_barcode')
                                            ->label('Kode Barcode')
                                            ->icon('heroicon-m-qr-code')
                                            ->copyable(),

                                        TextEntry::make('nama_alat')->label('Nama Alat'),

                                        TextEntry::make('status_alat')
                                            ->label('Status')
                                            ->badge()
                                            ->colors([
                                                'success' => 'Baik',
                                                'danger' => 'Rusak',
                                                'warning' => 'Hilang',
                                            ])
                                            ->icons([
                                                'heroicon-o-check-circle' => 'Baik',
                                                'heroicon-o-exclamation-triangle' => 'Rusak',
                                                'heroicon-o-wrench-screwdriver' => 'Hilang',
                                            ]),
                                    ])
                                    ->columnSpan(1),

                                Grid::make(1)
                                    ->schema([
                                        TextEntry::make('mobil.nomor_plat')
                                            ->label('Digunakan di Mobil')
                                            ->icon('heroicon-m-truck')
                                            ->visible(fn($record) => $record->mobil !== null),

                                        TextEntry::make('merek_alat')->label('Merek'),

                                        TextEntry::make('kategori_alat')->label('Kategori')->badge(),
                                    ])
                                    ->columnSpan(1),
                            ]),
                    ]),

                Section::make('Spesifikasi & Deskripsi')
                    ->description('Informasi teknis atau deskripsi tambahan alat.')
                    ->schema([
                        TextEntry::make('spesifikasi')->prose()->markdown()->hiddenLabel(),
                    ])
                    ->collapsible(),

                Section::make('QR Code')
                    ->description('Scan QR untuk melihat informasi alat secara cepat.')
                    ->schema([
                        TextEntry::make('qrcode')
                            ->label('QR Code')
                            ->html()
                            ->state(function ($record) {
                                $url = 'https://sigelat.web.id/scan/' . $record->kode_barcode;

                                $renderer = new \BaconQrCode\Renderer\ImageRenderer(
                                    new \BaconQrCode\Renderer\RendererStyle\RendererStyle(200),
                                    new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
                                );

                                $writer = new \BaconQrCode\Writer($renderer);
                                $qrSvg = $writer->writeString($url);
                                $base64 = base64_encode($qrSvg);

                                return '<img src="data:image/svg+xml;base64,' . $base64 . '" width="200" height="200">';
                            })
                            ->hiddenLabel()
                            ->extraAttributes(['class' => 'flex justify-center']),

                        // Actions::make([
                        //     Action::make('printQr')
                        //         ->label('🖨️ Print QR Code')
                        //         ->action(fn() => null)
                        //         ->color('primary')
                        //         ->extraAttributes([
                        //             'onclick' => 'window.print()',
                        //         ]),
                        // ])->alignment('center'),
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
