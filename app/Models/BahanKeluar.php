<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BahanKeluar extends Model
{
    protected $table = 'bahan_keluar';

    protected $fillable = [
        'idbahan_masuk',
        'bahan_id',
        'qty_keluar',
        'tanggal_keluar',
    ];

    protected $casts = [
        'tanggal_keluar' => 'datetime',
    ];

    protected static function booted()
    {
        static::saved(function ($model) {
            $model->bahan?->syncSisa();
        });

        static::deleted(function ($model) {
            $model->bahan?->syncSisa();
        });
    }

    public function bahanMasuk()
    {
        return $this->belongsTo(BahanMasuk::class, 'idbahan_masuk');
    }

    public function bahan()
    {
        return $this->belongsTo(Bahan::class, 'bahan_id');
    }
}
