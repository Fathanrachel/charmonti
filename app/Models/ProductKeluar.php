<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductKeluar extends Model
{
    protected $table = 'product_keluar';

    protected $fillable = [
        'idproduct_masuk',
        'product_id',
        'sisa',
        'qty_keluar',
        'tanggal_keluar',
    ];

    protected $casts = [
        'tanggal_keluar' => 'datetime',
    ];

    public function productMasuk()
    {
        return $this->belongsTo(ProductMasuk::class, 'idproduct_masuk');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
