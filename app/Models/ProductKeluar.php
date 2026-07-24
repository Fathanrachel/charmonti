<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductKeluar extends Model
{
    protected $table = 'product_keluar';

    protected $fillable = [
        'idproduct_masuk',
        'product_id',
        'qty_keluar',
        'tanggal_keluar',
    ];

    protected $casts = [
        'tanggal_keluar' => 'datetime',
    ];

    protected static function booted()
    {
        static::saved(function ($model) {
            $model->product?->syncSisa();
        });

        static::deleted(function ($model) {
            $model->product?->syncSisa();
        });
    }

    public function productMasuk()
    {
        return $this->belongsTo(ProductMasuk::class, 'idproduct_masuk');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
