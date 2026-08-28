<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variasi extends Model
{
    protected $table = 'variasis';

    protected $fillable = [
        'nama_variasi',
        'deskripsi',
    ];

    public function bahans()
    {
        return $this->hasMany(Bahan::class, 'variasi_id');
    }
}
