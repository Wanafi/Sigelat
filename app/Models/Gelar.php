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

    // Mobil yang digunakan dalam gelar
    public function mobil()
    {
        return $this->belongsTo(Mobil::class);
    }

    // User yang membuat entri gelar (opsional)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Pelaksana kegiatan (melalui tabel pelaksanas)
    // App\Models\Gelar.php

    public function pelaksanas()
    {
        return $this->hasMany(\App\Models\Pelaksana::class);
    }

    public function detailAlats()
    {
        return $this->hasMany(\App\Models\DetailGelar::class);
    }
}
