<?php

namespace App\Filament\Resources;

use App\Models\Alat;
use App\Models\User;
use App\Models\Gelar;
use App\Models\Mobil;
use App\Models\Pelaksana;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ActionGroup;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section as InfoSection;
use App\Filament\Resources\GelarResource\Pages;
use App\Filament\Resources\GelarResource\Pages\Formulir;
use App\Filament\Resources\GelarResource\Pages\ViewGelar;
use App\Filament\Resources\GelarResource\Pages\EditGelar;
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

                    Select::make('pelaksana_ids')
                        ->label('Petugas Pelaksana')
                        ->multiple()
                        ->options(User::pluck('name', 'id'))
                        ->searchable()
                        ->required(),
                ])
                ->columns(2),

            Section::make('Daftar Alat di Mobil')
                ->schema([
                    Repeater::make('detail_alats')
                        ->label('Detail Alat')
                        ->statePath('detail_alats') // penting!
                        ->schema([
                            TextInput::make('alat_id')
                                ->label('ID Alat')
                                ->hidden()
                                ->statePath('alat_id'),

                            TextInput::make('nama_alat')
                                ->label('Nama Alat')
                                ->disabled()
                                ->statePath('nama_alat'),

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
                                })
                                ->statePath('kondisi'),

                            TextInput::make('keterangan')
                                ->label('Keterangan')
                                ->placeholder('Opsional, isi jika ada catatan')
                                ->statePath('keterangan')
                                ->columnSpanFull(),
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
        $formData = request()->input('data', []);
        $alatList = $formData['detail_alats'] ?? [];
        $pelaksanaIds = $formData['pelaksana_ids'] ?? [];
        $statusGelar = 'Lengkap';

        foreach ($alatList as $alat) {
            if (!isset($alat['alat_id'], $alat['kondisi'])) {
                continue;
            }

            DB::table('detail_gelars')->insert([
                'gelar_id' => $record->id,
                'alat_id' => $alat['alat_id'],
                'status_alat' => $alat['kondisi'],
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

        $record->update(['status' => $statusGelar]);

        foreach ($pelaksanaIds as $userId) {
            Pelaksana::create([
                'gelar_id' => $record->id,
                'user_id' => $userId,
            ]);
        }
    }

    public static function afterSave(Gelar $record): void
    {
        DB::table('detail_gelars')->where('gelar_id', $record->id)->delete();
        Pelaksana::where('gelar_id', $record->id)->delete();

        self::afterCreate($record);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            InfoSection::make('Informasi Kegiatan')
                ->schema([
                    TextEntry::make('mobil.nomor_plat')->label('Nomor Plat Mobil'),
                    TextEntry::make('tanggal_cek')->label('Tanggal Cek')->date(),
                    TextEntry::make('status')->label('Status')->badge()->colors([
                        'success' => 'Lengkap',
                        'warning' => 'Tidak Lengkap',
                    ]),
                    TextEntry::make('pelaksanas')
                        ->label('Pelaksana')
                        ->state(fn($record) => $record->pelaksanas->map(fn($p) => $p->user->name)->join(', ')),
                ])
                ->columns(2),

            InfoSection::make('Daftar Alat yang Diperiksa')
                ->schema([
                    RepeatableEntry::make('detailAlats')
                        ->label('Detail Alat')
                        ->schema([
                            TextEntry::make('alat.nama_alat')->label('Nama Alat')->weight('bold'),
                            TextEntry::make('status_alat')->label('Kondisi')->badge()->color(fn(string $state): string => match ($state) {
                                'Bagus' => 'success',
                                'Rusak' => 'warning',
                                'Hilang' => 'danger',
                                default => 'gray',
                            }),
                            TextEntry::make('keterangan')->label('Keterangan')->placeholder('-')->default('-'),
                        ])
                        ->columns(3)
                        ->visible(fn($record) => $record->detailAlats()->exists())
                        ->contained()
                        ->columnSpanFull(),
                ])
                ->visible(fn($record) => $record->detailAlats()->exists()),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('mobil.nomor_plat')->label('Nomor Plat'),
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
                Tables\Columns\TextColumn::make('tanggal_cek')->label('Tanggal Cek')->date(),
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
