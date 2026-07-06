<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BahanMasuk extends Model
{
    protected $table = 'bahan_masuk';

    protected $fillable = [
        'bahan_id',
        'nama_bahan',
        'qty_masuk',
        'deskripsi',
        'tanggal_masuk',
    ];

    protected $casts = [
        'tanggal_masuk' => 'datetime',
    ];

    public function bahan()
    {
        return $this->belongsTo(Bahan::class, 'bahan_id');
    }

    public function bahanKeluar()
    {
        return $this->hasMany(BahanKeluar::class, 'idbahan_masuk');
    }
}
