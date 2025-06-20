<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Alat extends Model
{
    use HasFactory;

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
}