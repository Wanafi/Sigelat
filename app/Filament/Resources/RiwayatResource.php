<?php

namespace App\Filament\Resources\Laporan;

use Filament\Tables;
use App\Models\Riwayat;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\Laporan\RiwayatResource\Pages;

class RiwayatResource extends Resource
{
    protected static ?string $model = Riwayat::class;

    protected static ?string $navigationIcon = 'heroicon-m-clock';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = 'Riwayat Konfirmasi';

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pelapor')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('tanggal_cek')
                    ->label('Tanggal Cek')
                    ->date()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\BadgeColumn::make('riwayatable_type')
                    ->label('Jenis Laporan')
                    ->getStateUsing(function ($record) {
                        return match ($record->riwayatable_type) {
                            'App\\Models\\Mobil' => 'Mobil',
                            'App\\Models\\Alat' => 'Alat',
                            'App\\Models\\Gelar' => 'Gelar',
                            default => 'Lainnya',
                        };
                    })
                    ->colors([
                        'info' => 'Mobil',
                        'success' => 'Alat',
                        'warning' => 'Gelar',
                    ])
                    ->icons([
                        'heroicon-o-truck' => 'Mobil',
                        'heroicon-o-wrench' => 'Alat',
                        'heroicon-o-map' => 'Gelar',
                    ])
                    ->toggleable(),

                Tables\Columns\TextColumn::make('aksi')
                    ->label('Aksi')
                    ->getStateUsing(fn($record) => $record->aksi ?: '-')
                    ->searchable(false)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('catatan')
                    ->label('Catatan')
                    ->default('-')
                    ->searchable(false)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'gray' => 'Proses',
                        'success' => 'Selesai',
                    ])
                    ->icons([
                        'heroicon-o-clock' => 'Proses',
                        'heroicon-o-check-circle' => 'Selesai',
                    ])
                    ->sortable()
                    ->toggleable(),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make()->color('warning'),
                    DeleteAction::make()->color('danger'),
                ])->icon('heroicon-m-ellipsis-horizontal'),

                Action::make('markAsSelesai')
                    ->label('Tandai Selesai')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (Model $record) {
                        $record->status = 'Selesai';
                        $record->save();

                        // Jika riwayat untuk alat, update status_alat menjadi Bagus
                        if ($record->riwayatable_type === \App\Models\Alat::class && $record->riwayatable) {
                            $alat = $record->riwayatable;
                            $alat->status_alat = 'Bagus';
                            $alat->save();
                        }

                        session()->flash('message', 'Laporan berhasil ditandai sebagai selesai.');
                    })
                    ->visible(fn($record) => $record->status !== 'Selesai'),
            ])
            ->bulkActions([
                BulkAction::make('delete')
                    ->label('Hapus')
                    ->icon('heroicon-o-trash')
                    ->requiresConfirmation()
                    ->action(fn($records) => Riwayat::destroy($records->pluck('id'))),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRiwayats::route('/'),
            'view' => Pages\ViewRiwayat::route('/{record}'),
        ];
    }
}
