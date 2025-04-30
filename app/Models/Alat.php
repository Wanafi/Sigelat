<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Alat extends Model
{
    use HasFactory;
    public function mobil()
    {
        return $this->belongsTo(Mobil::class, 'mobil_id');
    }

    public function riwayats()
    {
        return $this->morphMany(Riwayat::class, 'riwayatable');
    }

    public function riwayatable()
{
    return $this->morphTo();
}

public function getStatusAlatAttribute($value)
{
    return ucfirst($value); // Mengubah 'proses' menjadi 'Proses'
}

    protected $fillable = [
        'kode_barcode',
        'nama_alat',
        'kategori_alat',
        'merek_alat',
        'spesifikasi',
        'tanggal_pembelian',
        'status_alat',
        'mobil_id',
    ];
}
