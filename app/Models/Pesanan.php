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
        'produk_id',
        'harga',
        'total_harga',
        'catatan',
        'durasi',
        'no_whatsapp',
        'payment_status',
        'snap_token',
        'midtrans_transaction_id',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    // Status pesanan: pending → dalam_antrian → diproses → terkirim
    // Status pembayaran: belum_dibayar → telah_dibayar

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function pemasukan()
    {
        return $this->hasOne(Pemasukan::class);
    }
}
