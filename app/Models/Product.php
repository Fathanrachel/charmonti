<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_name',
        'description',
        'price',
        'category',
        'image',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function productMasuk()
    {
        return $this->hasMany(ProductMasuk::class, 'product_id');
    }

    public function productKeluar()
    {
        return $this->hasMany(ProductKeluar::class, 'product_id');
    }

    public function deductStock(int $quantity): int
    {
        $needed = $quantity;
        $batches = $this->productMasuk()
            ->orderBy('tanggal_masuk', 'asc')
            ->get();

        foreach ($batches as $batch) {
            if ($needed <= 0) {
                break;
            }

            $alreadyOut = $batch->productKeluar()->sum('qty_keluar');
            $batchStock = $batch->qty_masuk - $alreadyOut;

            if ($batchStock > 0) {
                $deduct = min($needed, $batchStock);
                $batch->productKeluar()->create([
                    'product_id' => $this->id,
                    'sisa' => $batchStock - $deduct,
                    'qty_keluar' => $deduct,
                    'tanggal_keluar' => now(),
                ]);
                $needed -= $deduct;
            }
        }

        return $quantity - $needed;
    }

    public function getDynamicStockAttribute()
    {
        $masuk = $this->productMasuk()->sum('qty_masuk');
        $keluar = $this->productKeluar()->sum('qty_keluar');
        return (int) ($masuk - $keluar);
    }
}