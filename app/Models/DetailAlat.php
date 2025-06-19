<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailAlat extends Model
{
    protected $table = 'detail_alats';

    protected $primaryKey = null;
    public $incrementing = false; // karena pakai composite key

    protected $fillable = [
        'gelar_id',
        'mobil_id',
        'alat_id',
        'kondisi',
        'keterangan',
    ];

    public function gelar()
    {
        return $this->belongsTo(Gelar::class);
    }

    public function mobil()
    {
        return $this->belongsTo(Mobil::class);
    }

    public function alat()
    {
        return $this->belongsTo(Alat::class);
    }
}
