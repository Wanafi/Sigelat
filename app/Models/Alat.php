<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Alat extends Model
{
    use HasFactory;
    public function detail_alats()
    {
        return $this->hasMany(DetailAlat::class);
    }

    public function mobils()
    {
        return $this->belongsToMany(Mobil::class, 'detail_alats', 'alat_id', 'mobil_id')
            ->withPivot(['kondisi', 'keterangan']) // hapus 'gelar_id'
            ->withTimestamps();
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
