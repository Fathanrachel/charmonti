<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductMasuk extends Model
{
    protected $table = 'product_masuk';

    protected $fillable = [
        'product_id',
        'nama_product',
        'qty_masuk',
        'deskripsi',
        'tanggal_masuk',
    ];

    protected $casts = [
        'tanggal_masuk' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function productKeluar()
    {
        return $this->hasMany(ProductKeluar::class, 'idproduct_masuk');
    }
}
