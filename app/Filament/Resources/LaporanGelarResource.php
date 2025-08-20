<?php

namespace App\Filament\Resources;

use App\Models\Gelar;
use App\Models\Riwayat;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use App\Filament\Resources\LaporanGelarResource\Pages;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Actions\Action;

class LaporanGelarResource extends Resource
{
    protected static ?string $model = Gelar::class;
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $navigationLabel = 'Laporan Gelar';
    protected static ?string $pluralLabel = 'Laporan Kegiatan Gelar Alat';
    protected static ?string $navigationIcon = 'heroicon-m-rectangle-stack';

    public static function canCreate(): bool
    {
        return false;
    }
    public static function canEdit(Model $record): bool
    {
        return false;
    }
    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('mobil.nomor_plat')
                    ->label('Nomor Plat')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('tanggal_cek')
                    ->label('Tanggal Cek')
                    ->date()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'Lengkap',
                        'warning' => 'Tidak Lengkap',
                        'gray' => 'Proses',
                    ])
                    ->icons([
                        'heroicon-o-check-circle' => 'Lengkap',
                        'heroicon-o-exclamation-triangle' => 'Tidak Lengkap',
                        'heroicon-o-clock' => 'Proses',
                    ])
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Filter Status')
                    ->searchable()
                    ->options([
                        'Proses' => 'Proses',
                        'Lengkap' => 'Lengkap',
                        'Tidak Lengkap' => 'Tidak Lengkap',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\Action::make('konfirmasi')
                    ->label('Konfirmasi')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->form([
                        TextInput::make('aksi')
                            ->label('Tindakan')
                            ->required(),

                        Textarea::make('catatan')
                            ->label('Catatan')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (Model $record, array $data) {
                        $record->update(['status' => 'Lengkap']);

                        Riwayat::create([
                            'riwayatable_id' => $record->id,
                            'riwayatable_type' => get_class($record),
                            'user_id' => auth()->id(),
                            'status' => 'Selesai',
                            'tanggal_cek' => now(),
                            'aksi' => $data['aksi'],
                            'catatan' => $data['catatan'],
                        ]);

                        Notification::make()
                            ->title('Berhasil')
                            ->body('Laporan Gelar telah dikonfirmasi.')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Informasi Kegiatan Gelar')
                ->schema([
                    TextEntry::make('mobil.nomor_plat')->label('Nomor Plat'),
                    TextEntry::make('tanggal_cek')->label('Tanggal Cek')->date(),
                    TextEntry::make('status')
                        ->label('Status')
                        ->badge()
                        ->color(fn($state) => match ($state) {
                            'Lengkap' => 'success',
                            'Tidak Lengkap' => 'warning',
                            'Proses' => 'gray',
                            default => 'gray',
                        }),
                ])
                ->columns(2),

            // Konfirmasi ditaruh di bawah pakai Actions::make
            Actions::make([
                Action::make('konfirmasi')
                    ->label('Konfirmasi Kegiatan')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->form([
                        TextInput::make('aksi')
                            ->label('Tindakan')
                            ->required(),

                        Textarea::make('catatan')
                            ->label('Catatan')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (Model $record, array $data) {
                        $record->update(['status' => 'Lengkap']);

                        Riwayat::create([
                            'riwayatable_id' => $record->id,
                            'riwayatable_type' => get_class($record),
                            'user_id' => auth()->id(),
                            'status' => 'Selesai',
                            'tanggal_cek' => now(),
                            'aksi' => $data['aksi'],
                            'catatan' => $data['catatan'],
                        ]);

                        Notification::make()
                            ->title('Berhasil')
                            ->body('Konfirmasi kegiatan gelar berhasil.')
                            ->success()
                            ->send();
                    }),
            ]),
        ]);
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLaporanGelars::route('/'),
            'view' => Pages\ViewLaporanGelar::route('/{record}'),
        ];
    }
        public static function getPluralLabel(): string
    {
        return 'Laporan Gelar Alat';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

}
