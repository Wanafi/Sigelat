<?php

namespace App\Models;

use App\Models\DetailAlat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mobil extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_plat',
        'merk_mobil',
        'no_seri',
        'no_unit',
        'nama_tim',
        'status_mobil',
    ];

    public function alats()
    {
        return $this->hasMany(Alat::class);
    }

    public function gelars()
    {
        return $this->hasMany(Gelar::class);
    }
}
