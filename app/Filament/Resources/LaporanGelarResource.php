<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Gelar;
use App\Models\Riwayat;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Pages\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Resources\LaporanGelarResource\Pages;

class LaporanGelarResource extends Resource
{
    protected static ?string $model = Gelar::class;
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $navigationLabel = 'Laporkan Gelar';
    protected static ?string $pluralLabel = 'Daftar Laporan Gelar Alat';
    protected static ?string $navigationIcon = 'heroicon-m-rectangle-stack';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('mobil.nomor_plat')
                    ->label('Mobil')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pelapor')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable()
                    ->searchable()
                    ->toggleable()
                    ->colors([
                        'success' => 'Lengkap',
                        'warning' => 'TidakLengkap',
                        'gray' => 'Proses',
                    ])
                    ->icons([
                        'heroicon-o-check-circle' => 'Lengkap',
                        'heroicon-o-exclamation-triangle' => 'Tidak Lengkap',
                        'heroicon-o-clock' => 'Proses',
                    ]),

                Tables\Columns\TextColumn::make('tanggal_cek')
                    ->label('Tanggal Cek')
                    ->date()
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->searchable()
                    ->preload()
                    ->label('Filter Status')
                    ->indicator('Filter By')
                    ->options([
                        'Lengkap' => 'Lengkap',
                        'Tidak Lengkap' => 'Tidak Lengkap',
                        'proses' => 'Proses',
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
            
                Tables\Actions\Action::make('konfirmasi')
                    ->label('Konfirmasi')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->form([
                        Forms\Components\TextInput::make('aksi')
                            ->label('Aksi')
                            ->required(),
            
                        Forms\Components\Textarea::make('catatan')
                            ->label('Catatan')
                            ->rows(3)
                            ->required(),
                    ])
                    ->action(function (Model $record, array $data) {
                        /** @var Gelar $record */
                        $record->status = 'Proses';
                        $record->save();
            
                        \App\Models\Riwayat::create([
                            'riwayatable_id' => $record->id,
                            'riwayatable_type' => get_class($record),
                            'status' => 'proses',
                            'user_id' => auth()->id(),
                            'tanggal_cek' => now()->toDateString(),
                            'aksi' => $data['aksi'],
                            'catatan' => $data['catatan'],
                        ]);
            
                        session()->flash('message', 'Laporan Gelar berhasil diproses!');
                    })
                    ->visible(fn ($record) => $record->status !== 'Proses'),
            ])
            
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListLaporanGelars::route('/'),
            'create' => Pages\CreateLaporanGelar::route('/create'),
            'view' => Pages\ViewLaporanGelar::route('/{record}'),
            'edit' => Pages\EditLaporanGelar::route('/{record}/edit'),
        ];
    }
}
