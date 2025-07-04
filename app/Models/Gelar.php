<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gelar extends Model
{
    use HasFactory;

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
}
