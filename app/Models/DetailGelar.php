<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailGelar extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'gelar_id',
        'alat_id',
        'status_alat',
        'keterangan',
    ];


    public function gelar()
    {
        return $this->belongsTo(Gelar::class);
    }

    public function alat()
    {
        return $this->belongsTo(Alat::class);
    }

    protected static $logAttributes = ['status_alat']; // tambahkan atribut yang ingin dicatat
    protected static $logName = 'alat';
    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status_alat'])
            ->logOnlyDirty()
            ->useLogName('alat')
            ->setDescriptionForEvent(fn(string $eventName) => "Status alat telah di{$eventName}");
    }
}
