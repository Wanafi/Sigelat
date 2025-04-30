<?php

namespace App\Models;

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
        return $this->hasMany(Alat::class);
    }

    public function riwayatable()
{
    return $this->morphTo();
}

public function getStatusMobilAttribute($value)
{
    return ucfirst($value); // Mengubah 'proses' menjadi 'Proses'
}

    protected $fillable = [
        'nomor_plat',
        'merk_mobil',
        'no_unit',
        'status_mobil',
    ];
}
