<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BahanMasuk extends Model
{
    protected $table = 'bahan_masuk';

    protected $fillable = [
        'bahan_id',
        'qty_masuk',
        'deskripsi',
        'tanggal_masuk',
    ];

    protected $casts = [
        'tanggal_masuk' => 'datetime',
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

    public function bahan()
    {
        return $this->belongsTo(Bahan::class, 'bahan_id');
    }

    public function bahanKeluar()
    {
        return $this->hasMany(BahanKeluar::class, 'idbahan_masuk');
    }
}
