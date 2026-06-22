<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Model Pemasukan merepresentasikan tabel 'pemasukans' di database.
// Digunakan untuk mencatat arus kas masuk (cash in) terkait keuangan perusahaan.
class Pemasukan extends Model
{
    // $fillable melindungi dari Mass Assignment Vulnerability.
    // Hanya kolom-kolom di bawah ini yang bisa diisi data secara langsung.
    protected $fillable = [
        'tanggal',    // Tanggal transaksi kas masuk
        'jumlah',     // Nominal uang yang masuk
        'keterangan', // Deskripsi/catatan transaksi
        'kategori',   // Kategori pemasukan (misal: jasa, operasional)
        'status',     // Status catatan pemasukan
        'catatan',    // Tambahan detail khusus transaksi
        'user_id',    // Foreign key ke tabel users (Petugas yang mencatat)
        'pesanan_id', // Foreign key opsional ke tabel pesanans (Jika pemasukan ini berasal dari pesanan)
    ];

    // $casts memodifikasi tipe data dari database saat diambil ke aplikasi.
    protected $casts = [
        'tanggal' => 'date',         // Format menjadi object Date (tanpa jam)
        'jumlah' => 'decimal:2',     // Format menjadi desimal dengan 2 angka di belakang koma
        'created_at' => 'datetime',  // Timestamp bawaan Laravel
        'updated_at' => 'datetime',
    ];

    // Relasi Many-to-One: Setiap pencatatan Pemasukan dilakukan oleh 1 User (Petugas)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi Many-to-One: Setiap pencatatan Pemasukan dapat terkait dengan 1 Pesanan
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }
}
