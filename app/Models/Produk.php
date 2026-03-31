<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $fillable = [
        'nama',
        'jenis',
        'harga',
        'satuan',
        'deskripsi',
        'gambar'
    ];
}