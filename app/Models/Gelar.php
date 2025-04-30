<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gelar extends Model
{
    use HasFactory;

    protected $fillable = [
        'mobil_id',
        'status',
        'tanggal_cek',
        'user_id',
    ];

    public function riwayats()
    {
        return $this->morphMany(Riwayat::class, 'riwayatable');
    }

    public function mobil()
    {
        return $this->belongsTo(Mobil::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
