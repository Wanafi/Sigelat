<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Alat extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'mobil_id',
        'kode_barcode',
        'nama_alat',
        'kategori_alat',
        'merek_alat',
        'spesifikasi',
        'tanggal_pembelian',
        'status_alat',
    ];

    public function mobil()
    {
        return $this->belongsTo(Mobil::class);
    }

    public function detailGelars()
    {
        return $this->hasMany(DetailGelar::class);
    }

    public function riwayats()
    {
        return $this->hasMany(Riwayat::class);
    }

    protected static $logAttributes = ['status_alat'];
    protected static $logName = 'alat';
    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nama_alat', 'status_alat'])
            ->useLogName('alat')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Status alat telah di{$eventName}");
    }
}
