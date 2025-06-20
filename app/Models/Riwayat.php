<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Riwayat extends Model
{
    use HasFactory;

    protected $fillable = [
        'alat_id',
        'riwayatable_type',
        'riwayatable_id',
        'user_id',
        'tanggal_cek',
        'status',
        'aksi',
        'catatan',
    ];

    public function alat()
    {
        return $this->belongsTo(Alat::class);
    }

    public function riwayatable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}