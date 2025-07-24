<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gelar extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'mobil_id',
        'user_id',
        'status',
        'tanggal_cek',
    ];

    public function mobil()
    {
        return $this->belongsTo(Mobil::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pelaksanas()
    {
        return $this->hasMany(\App\Models\Pelaksana::class);
    }

    public function detailAlats()
    {
        return $this->hasMany(\App\Models\DetailGelar::class);
    }

    public function detailGelars()
    {
        return $this->hasMany(\App\Models\DetailGelar::class);
    }

    public function riwayats()
    {
        return $this->morphMany(Riwayat::class, 'riwayatable');
    }

    public function sudahDikonfirmasi(): bool
    {
        return $this->riwayats()->exists();
    }

    public function getRiwayatableRiwayatTerbaruAttribute()
    {
        return \App\Models\Riwayat::where('riwayatable_type', self::class)
            ->where('riwayatable_id', $this->id)
            ->latest('created_at')
            ->with('user')
            ->first();
    }

        public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['status', 'tanggal_cek']);
        // Chain fluent methods for configuration options
    }
}
