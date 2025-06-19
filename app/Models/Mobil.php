<?php

namespace App\Models;

use App\Models\DetailAlat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mobil extends Model
{
    use HasFactory;
    public function riwayats()
    {
        return $this->morphMany(Riwayat::class, 'riwayatable');
    }
    public function alats()
    {
        return $this->belongsToMany(Alat::class, 'detail_alats', 'mobil_id', 'alat_id')
            ->withPivot(['kondisi', 'keterangan']) // hapus 'gelar_id'
            ->withTimestamps();
    }



    public function riwayatable()
    {
        return $this->morphTo();
    }

    public function getStatusMobilAttribute($value)
    {
        return ucfirst($value); // Mengubah 'proses' menjadi 'Proses'
    }

    public function detail_alats()
    {
        return $this->hasMany(DetailAlat::class);
    }

    protected $fillable = [
        'nomor_plat',
        'nama_tim',
        'merk_mobil',
        'no_unit',
        'status_mobil',
    ];
}
