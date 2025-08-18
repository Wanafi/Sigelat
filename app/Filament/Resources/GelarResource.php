<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Alat;
use App\Models\User;
use Filament\Tables;
use App\Models\Gelar;
use App\Models\Mobil;
use Filament\Forms\Form;
use App\Models\Pelaksana;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Log;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Actions\DeleteAction;
use Filament\Forms\Components\ToggleButtons;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use App\Filament\Resources\GelarResource\Pages;
use Filament\Infolists\Components\RepeatableEntry;
use App\Filament\Resources\GelarResource\Pages\Formulir;
use App\Filament\Resources\GelarResource\Pages\EditGelar;
use App\Filament\Resources\GelarResource\Pages\ViewGelar;
use Filament\Infolists\Components\Section as InfoSection;
use App\Filament\Resources\GelarResource\Pages\ListGelars;
use App\Filament\Resources\GelarResource\Pages\CreateGelar;

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

                    TagsInput::make('pelaksana')
                        ->label('Nama Pelaksana')
                        ->placeholder('Ketik nama, tekan Enter untuk tambah')
                        ->separator(',')
                        ->required(),
                ])
                ->columns(2),

            Section::make('Daftar Alat di Mobil')
                ->schema([
                    Repeater::make('detail_alats')
                        ->statePath('detail_alats') // penting!
                        ->schema([
                            TextInput::make('alat_id')
                                ->label('ID Alat')
                                ->hidden(),

                            TextInput::make('nama_alat')
                                ->label('Nama Alat')
                                ->disabled(),

                            ToggleButtons::make('status_alat')
                                ->label('Kondisi Alat')
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
                                ->required()
                                ->inline()
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $get) {
                                    $alatId = $get('alat_id');
                                    if ($alatId && in_array($state, ['Baik', 'Rusak', 'Hilang'])) {
                                        $alat = Alat::find($alatId);
                                        if ($alat && $alat->status_alat !== $state) {
                                            $alat->status_alat = $state;
                                            $alat->save(); // ✅ Activity log akan aktif di sini
                                        }
                                    }
                                })

                                ->statePath('kondisi'),

                            TextInput::make('keterangan')
                                ->label('Keterangan')
                                ->placeholder('Opsional, isi jika ada catatan'),

                            FileUpload::make('foto_kondisi')
                                ->label('Foto Kondisi Alat')
                                ->directory('foto-kondisi')
                                ->image()
                                ->imageEditor()
                                ->imagePreviewHeight('150')
                                ->maxSize(2048)
                                ->extraAttributes([
                                    'accept' => 'image/*',
                                    'capture' => 'environment', // aktifkan kamera belakang
                                ])
                                ->columnspanfull(),
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
        $detailAlats = $record->form->getState()['detail_alats'] ?? [];
        $statusGelar = 'Lengkap';

        foreach ($detailAlats as $alat) {
            if (!isset($alat['alat_id'], $alat['status_alat'])) {
                continue;
            }

            DB::table('detail_gelars')->insert([
                'gelar_id' => $record->id,
                'alat_id' => $alat['alat_id'],
                'status_alat' => $alat['status_alat'],
                'keterangan' => $alat['keterangan'] ?? null,
                'foto_kondisi' => $alat['foto_kondisi'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($alat['status_alat'] === 'Hilang') {
                $statusGelar = 'Tidak Lengkap';
            }

            $alatModel = Alat::find($alat['alat_id']);
            if ($alatModel && $alatModel->status_alat !== $alat['status_alat']) {
                $alatModel->status_alat = $alat['status_alat'];
                $alatModel->save();
            }
        }

        $record->update(['status' => $statusGelar]);
    }

    public static function afterSave(Gelar $record): void
    {
        DB::table('detail_gelars')->where('gelar_id', $record->id)->delete();
        Pelaksana::where('gelar_id', $record->id)->delete();

        self::afterCreate($record); // Sudah diperbaiki agar activity log aktif
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            InfoSection::make('Informasi Kegiatan')
                ->schema([
                    TextEntry::make('mobil.nomor_plat')->label('Nomor Plat Mobil')
                        ->extraAttributes([
                            'class' => 'px-3 py-2 rounded-xl bg-white/30 backdrop-blur-md border border-white/20 
                shadow-lg text-gray-900',
                            'style' => 'transform: perspective(800px) translateZ(10px);',
                        ]),
                    TextEntry::make('tanggal_cek')->label('Tanggal Cek')->date()
                        ->extraAttributes([
                            'class' => 'px-3 py-2 rounded-xl bg-white/30 backdrop-blur-md border border-white/20 
                shadow-lg text-gray-900',
                            'style' => 'transform: perspective(800px) translateZ(10px);',
                        ]),
                    TextEntry::make('status')->label('Status')->badge()->colors([
                        'success' => 'Lengkap',
                        'warning' => 'Tidak Lengkap',
                    ])
                        ->extraAttributes([
                            'class' => 'px-3 py-2 rounded-xl bg-white/30 backdrop-blur-md border border-white/20 
                shadow-lg text-gray-900',
                            'style' => 'transform: perspective(800px) translateZ(10px);',
                        ]),
                    TextEntry::make('pelaksana')
                        ->label('Pelaksana')
                        ->extraAttributes([
                            'class' => 'px-3 py-2 rounded-xl bg-white/30 backdrop-blur-md border border-white/20 
                shadow-lg text-gray-900',
                            'style' => 'transform: perspective(800px) translateZ(10px);',
                        ]),
                ])
                ->columns(2),

            InfoSection::make('Daftar Alat yang Diperiksa')
                ->schema([
                    RepeatableEntry::make('detailAlats')
                        ->label('Detail Alat')
                        ->schema([
                            TextEntry::make('alat.nama_alat')
                                ->label('Nama Alat')
                                ->weight('bold')
                                ->extraAttributes([
                                    'class' => 'px-3 py-2 rounded-xl bg-white/30 backdrop-blur-md border border-white/20 
                shadow-lg text-gray-900',
                                    'style' => 'transform: perspective(800px) translateZ(10px);',
                                ]),

                            TextEntry::make('status_alat')
                                ->label('Kondisi')
                                ->badge()
                                ->color(fn(string $state): string => match ($state) {
                                    'Baik' => 'success',
                                    'Rusak' => 'warning',
                                    'Hilang' => 'danger',
                                    default => 'gray',
                                })
                                ->extraAttributes([
                                    'class' => 'px-3 py-2 rounded-xl bg-white/30 backdrop-blur-md border border-white/20 
                shadow-lg text-gray-900',
                                    'style' => 'transform: perspective(800px) translateZ(10px);',
                                ]),

                            TextEntry::make('keterangan')
                                ->label('Keterangan')
                                ->placeholder('-')
                                ->default('-')
                                ->extraAttributes([
                                    'class' => 'px-3 py-2 rounded-xl bg-white/30 backdrop-blur-md border border-white/20 
                shadow-lg text-gray-900',
                                    'style' => 'transform: perspective(800px) translateZ(10px);',
                                ]),

                            TextEntry::make('foto_kondisi')
                                ->label('Lihat Foto')
                                ->url(fn($state) => filled($state)
                                    ? asset('storage/' . $state)
                                    : null)
                                ->openUrlInNewTab()
                                ->hidden(fn($state) => blank($state))
                                ->badge() // biar kelihatan kayak tombol kecil
                                ->color('info')
                                ->extraAttributes([
                                    'class' => 'inline-block px-4 py-2 rounded-xl bg-white/20 backdrop-blur-md 
                text-white shadow-lg border border-white/30 
                hover:bg-white/30 cursor-pointer transition-all duration-200',
                                ]),
                        ])
                        ->columns(2)
                        ->visible(fn($record) => $record->detailAlats()->exists())
                        ->contained()
                        ->columnSpanFull()
                ])
                ->visible(fn($record) => $record->detailAlats()->exists()),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('mobil.nomor_plat')->label('Nomor Plat'),
                Tables\Columns\TextColumn::make('tanggal_cek')->label('Tanggal Cek')->date(),
                Tables\Columns\TextColumn::make('status')->label('Status')
                    ->badge()
                    ->colors([
                        'success' => 'Lengkap',
                        'warning' => 'Tidak Lengkap',
                    ])
                    ->icons([
                        'heroicon-o-check-circle' => 'Lengkap',
                        'heroicon-o-exclamation-triangle' => 'Tidak Lengkap',
                    ]),
            ])
            ->actions([
                ActionGroup::make([
                    Action::make("form_gelat")
                        ->label('Cetak Form')
                        ->icon('heroicon-s-printer')
                        ->color('success')
                        ->action(function ($record) {
                            if (! $record->sudahDikonfirmasi()) {
                                \Filament\Notifications\Notification::make()
                                    ->title('Belum Dikonfirmasi')
                                    ->body('Formulir hanya dapat dicetak setelah dikonfirmasi di Laporan Gelar.')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            return redirect()->to(GelarResource::getUrl("formulir", ['record' => $record->id]));
                        })
                        ->requiresConfirmation(false),
                    ViewAction::make(),
                    EditAction::make()->color('warning'),
                    DeleteAction::make()->color('danger'),
                ])
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGelars::route('/'),
            'create' => CreateGelar::route('/create'),
            'view' => ViewGelar::route('/{record}'),
            'edit' => EditGelar::route('/{record}/edit'),
            'formulir' => Formulir::route('/{record}/formulir'),
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
