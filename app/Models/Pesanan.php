<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// Model Pesanan merepresentasikan tabel 'pesanans' di database.
// Model ini adalah jantung dari aplikasi pemesanan karena menyimpan seluruh data transaksi.
class Pesanan extends Model
{
    use SoftDeletes;
    // $fillable menentukan kolom-kolom mana saja yang diizinkan untuk diisi secara massal (Mass Assignment).
    // Ini adalah fitur keamanan dari Laravel untuk mencegah user memanipulasi kolom yang tidak seharusnya.
    protected $fillable = [
        'nomor', // Nomor unik pesanan (invoice)
        'nama', // Nama pesanan/proyek
        'tipe', // Tipe pesanan
        'jumlah', // Kuantitas pesanan
        'alamat_penjemputan', // Alamat asal logistik
        'alamat_pengiriman', // Alamat tujuan pengiriman
        'status', // Status progres pesanan
        'description', // Deskripsi detail
        'user_id', // Relasi ke user pemesan
        'produk_id', // Relasi ke produk katalog (jika ada)
        'harga', // Harga per satuan
        'ongkos_kirim', // Ongkos kirim
        'jarak', // Jarak pengiriman (km)
        'total_harga', // Total harga keseluruhan
        'catatan', // Catatan tambahan dari pembeli
        'durasi', // Estimasi waktu pengerjaan/pengiriman
        'no_whatsapp', // Nomor kontak pembeli
        'payment_status', // Status pembayaran (Midtrans)
        'snap_token', // Token transaksi Midtrans untuk popup pembayaran
        'midtrans_transaction_id', // ID Transaksi dari Midtrans
        'paid_at', // Waktu pembayaran berhasil
        'alasan_penolakan', // Alasan jika pesanan ditolak admin
    ];

    // $casts mengubah tipe data kolom saat diakses.
    // Di sini 'paid_at' diubah otomatis menjadi object Carbon (datetime) agar mudah diformat.
    protected $casts = [
        'paid_at' => 'datetime',
    ];

    // Status pesanan: pending → dalam_antrian → diproses → terkirim
    // Status pembayaran: belum_dibayar → telah_dibayar

    // Relasi Many-to-One: Setiap 1 Pesanan dimiliki oleh 1 User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi Many-to-One: Setiap 1 Pesanan merujuk pada 1 Produk (opsional)
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    // Relasi One-to-One: Setiap 1 Pesanan bisa masuk ke 1 catatan Pemasukan (keuangan)
    public function pemasukan()
    {
        return $this->hasOne(Pemasukan::class);
    }
}
