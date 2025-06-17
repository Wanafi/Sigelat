<?php

namespace App\Filament\Resources\Laporan;

use Filament\Tables;
use App\Models\Riwayat;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ActionGroup;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\Laporan\RiwayatResource\Pages;

class RiwayatResource extends Resource
{
    protected static ?string $model = Riwayat::class;

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    protected static ?string $navigationIcon = 'heroicon-m-clock';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = 'Riwayat Konfirmasi';
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
                    ->toggleable()  // Menambahkan toggleable untuk kolom yang bisa disembunyikan
                    ->searchable(),

                Tables\Columns\TextColumn::make('tanggal_cek')
                    ->label('Tanggal Cek')
                    ->date()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\BadgeColumn::make('riwayatable_type')
                    ->label('Jenis Laporan')
                    ->formatStateUsing(fn($state) => class_basename($state))
                    ->getStateUsing(function ($record) {
                        if ($record->riwayatable_type === 'App\\Models\\Mobil') return 'Mobil';
                        if ($record->riwayatable_type === 'App\\Models\\Alat') return 'Alat';
                        if ($record->riwayatable_type === 'App\\Models\\Gelar') return 'Gelar';
                        return 'Lainnya';
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
                    ->searchable(false)  // Menonaktifkan pencarian di kolom aksi
                    ->toggleable()
                    ->getStateUsing(fn($record) => $record->aksi ? $record->aksi : '-'),  // Menambahkan nilai default jika kosong

                Tables\Columns\TextColumn::make('catatan')
                    ->label('Catatan')
                    ->placeholder('-')
                    ->searchable(false)
                    ->toggleable(),  // Menambahkan toggleable

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'success' => 'Selesai',
                        'gray' => 'Proses',
                    ])
                    ->icons([
                        'heroicon-o-check-circle' => 'Selesai',
                        'heroicon-o-clock' => 'Proses',
                    ])
                    ->sortable()  // Menambahkan sortable agar user bisa mengurutkan berdasarkan status
                    ->toggleable(),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make()
                        ->color('warning'),
                    DeleteAction::make()
                        ->color('danger'),
                ])->icon('heroicon-m-ellipsis-horizontal'),
                Tables\Actions\Action::make('Selesai')
                    // ->label('Tandai Selesai')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function (Model $record) {
                        // Mengubah status menjadi selesai
                        $record->status = 'Selesai';
                        $record->save();

                        if ($record->riwayatable_type === \App\Models\Alat::class) {
                            $alat = $record->riwayatable;
                            if ($alat) {
                                $alat->status_alat = 'Bagus';
                                $alat->save();
                            }
                        }

                        // Menampilkan pesan bahwa laporan telah selesai
                        session()->flash('message', 'Laporan berhasil ditandai sebagai selesai!');
                    }),
            ])

            ->bulkActions([
                Tables\Actions\BulkAction::make('delete')
                    ->label('Hapus')
                    ->icon('heroicon-o-trash')
                    ->action(function ($records) {
                        // Mengambil ID dari setiap record dan menghapusnya
                        Riwayat::destroy($records->pluck('id'));  // Menggunakan pluck untuk mengambil ID dari collection
                    }),
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
