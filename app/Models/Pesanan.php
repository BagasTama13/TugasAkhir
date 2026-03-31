<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $fillable = [
        'nomor',
        'nama',
        'tipe',
        'jumlah',
        'alamat_penjemputan',
        'alamat_pengiriman',
        'status',
        'description',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
