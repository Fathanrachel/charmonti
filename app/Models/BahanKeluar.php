<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BahanKeluar extends Model
{
    protected $table = 'bahan_keluar';

    protected $fillable = [
        'idbahan_masuk',
        'bahan_id',
        'sisa',
        'qty_keluar',
        'tanggal_keluar',
    ];

    protected $casts = [
        'tanggal_keluar' => 'datetime',
    ];

    public function bahanMasuk()
    {
        return $this->belongsTo(BahanMasuk::class, 'idbahan_masuk');
    }

    public function bahan()
    {
        return $this->belongsTo(Bahan::class, 'bahan_id');
    }
}
