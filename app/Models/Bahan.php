<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bahan extends Model
{
    protected $table = 'bahan';

    protected $fillable = [
        'nama_bahan',
        'description',
        'price',
        'image',
        'sisa',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sisa'  => 'integer',
    ];

    public function bahanMasuk()
    {
        return $this->hasMany(BahanMasuk::class, 'bahan_id');
    }

    public function bahanKeluar()
    {
        return $this->hasMany(BahanKeluar::class, 'bahan_id');
    }

    public function customBahanOrderItems()
    {
        return $this->hasMany(CustomBahanOrderItem::class, 'bahan_id');
    }

    // Dynamic stock accessor (Centralized sisa stock with batch fallback)
    public function getDynamicStockAttribute()
    {
        $masuk = $this->bahanMasuk()->sum('qty_masuk');
        $keluar = $this->bahanKeluar()->sum('qty_keluar');
        $calculated = (int) ($masuk - $keluar);
        return max(0, $calculated);
    }

    public function syncSisa(): void
    {
        $this->update(['sisa' => $this->getDynamicStockAttribute()]);
    }

    public function deductStock(int $quantity): int
    {
        $needed = $quantity;
        $batches = $this->bahanMasuk()
            ->orderBy('tanggal_masuk', 'asc')
            ->get();

        foreach ($batches as $batch) {
            if ($needed <= 0) {
                break;
            }

            $alreadyOut = BahanKeluar::where('idbahan_masuk', $batch->id)->sum('qty_keluar');
            $batchStock = $batch->qty_masuk - $alreadyOut;

            if ($batchStock > 0) {
                $deduct = min($needed, $batchStock);
                BahanKeluar::create([
                    'idbahan_masuk'  => $batch->id,
                    'bahan_id'       => $this->id,
                    'qty_keluar'     => $deduct,
                    'tanggal_keluar' => now(),
                ]);
                $needed -= $deduct;
            }
        }

        $this->syncSisa();

        return $needed; // Returns remaining quantity if out of stock
    }
}
