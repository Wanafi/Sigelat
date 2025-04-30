<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Riwayat extends Model
{
    use HasFactory;

    protected $fillable = [
        'riwayatable_id',
        'riwayatable_type',
        'status',
        'tanggal_cek',
        'aksi',
        'catatan',
        'user_id',
    ];

    /**
     * Relasi polymorphic ke Gelar, Mobil, atau Alat.
     */
    public function riwayatable()
    {
        return $this->morphTo();
    }

    /**
     * Relasi ke pengguna (pelapor).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusRiwayatAttribute($value)
{
    return ucfirst($value); // Mengubah 'proses' menjadi 'Proses'
}
}
