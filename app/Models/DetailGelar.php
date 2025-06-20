<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailGelar extends Model
{
    use HasFactory;

    protected $fillable = [
        'gelar_id',
        'alat_id',
        'status_alat',
    ];

    public function gelar()
    {
        return $this->belongsTo(Gelar::class);
    }

    public function alat()
    {
        return $this->belongsTo(Alat::class);
    }
}