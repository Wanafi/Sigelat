<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Mobil;
use App\Models\Riwayat;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Filters\MultiSelectFilter;
use App\Filament\Resources\LaporanMobilResource\Pages;

class LaporanMobilResource extends Resource
{
    protected static ?string $model = Mobil::class;

    protected static ?string $navigationIcon = 'heroicon-m-rectangle-stack';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $navigationLabel = 'Laporkan Mobil';

    public static function shouldRegisterNavigation(): bool
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
                Tables\Columns\TextColumn::make('nomor_plat')->searchable(),
                Tables\Columns\TextColumn::make('merk_mobil'),
                Tables\Columns\TextColumn::make('no_unit'),
                Tables\Columns\BadgeColumn::make('status_mobil')
                    ->label('Status')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'Aktif' => 'Aktif',
                        'Tidak Aktif' => 'Tidak Aktif',
                        'Dalam Perbaikan' => 'Dalam Perbaikan',
                        default => ucfirst($state),
                    })
                    ->color(fn($state) => match ($state) {
                        'Aktif' => 'success',
                        'Tidak Aktif' => 'warning',
                        'Dalam Perbaikan' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn($state) => match ($state) {
                        'Tidak Aktif' => 'heroicon-o-exclamation-triangle',
                        'Dalam Perbaikan' => 'heroicon-o-wrench-screwdriver',
                        default => 'heroicon-o-information-circle',
                    }),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                MultiSelectFilter::make('status_mobil')
                    ->label('Status Mobil')
                    ->options([
                        'Aktif' => 'Aktif',
                        'Tidak Aktif' => 'Tidak Aktif',
                        'Dalam Perbaikan' => 'Dalam Perbaikan',
                    ])
                    ->default(['Tidak Aktif', 'Dalam Perbaikan']),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make()->color('warning'),
                    DeleteAction::make()->color('danger'),
                    Tables\Actions\Action::make('konfirmasi')
                        ->label('Konfirmasi')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->form([
                            TextInput::make('aksi')
                                ->label('Aksi')
                                ->required(),
                            Textarea::make('catatan')
                                ->label('Catatan')
                                ->rows(3)
                                ->required(),
                        ])
                        ->action(function (Model $record, array $data) {
                            // Tidak ubah status_mobil langsung — hanya buat riwayat
                            Riwayat::create([
                                'riwayatable_id' => $record->id,
                                'riwayatable_type' => get_class($record),
                                'status' => 'Proses',
                                'user_id' => auth()->id(),
                                'tanggal_cek' => now()->toDateString(),
                                'aksi' => $data['aksi'],
                                'catatan' => $data['catatan'],
                            ]);

                            session()->flash('message', 'Laporan Mobil berhasil dikonfirmasi.');
                        }),
                ]),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLaporanMobils::route('/'),
            'create' => Pages\CreateLaporanMobil::route('/create'),
            'view' => Pages\ViewLaporanMobil::route('/{record}'),
            'edit' => Pages\EditLaporanMobil::route('/{record}/edit'),
        ];
    }
}
