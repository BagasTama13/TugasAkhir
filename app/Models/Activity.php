<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Model Activity merepresentasikan tabel 'activities' di database.
// Sering digunakan untuk fitur "Audit Log" atau melacak riwayat tindakan pengguna (Activity Tracking).
class Activity extends Model
{
    // $fillable adalah field/kolom tabel yang diizinkan untuk diisi secara massal (Mass Assignment Protection)
    protected $fillable = [
        'user_id',     // User siapa yang melakukan tindakan
        'action',      // Jenis tindakan (misal: 'created', 'updated', 'deleted')
        'entity_type', // Jenis model/tabel yang dimanipulasi (misal: 'App\Models\Pesanan')
        'entity_id',   // ID data yang dimanipulasi
        'description', // Keterangan deskripsi log (misal: "User A merubah status pesanan B")
        'old_values',  // Menyimpan nilai data lama sebelum diubah (format JSON)
        'new_values'   // Menyimpan nilai data baru setelah diubah (format JSON)
    ];

    // $casts mengatur konversi otomatis tipe data saat dibaca oleh Laravel
    protected $casts = [
        'old_values' => 'array', // Dikonversi dari kolom teks JSON di DB ke array PHP
        'new_values' => 'array', // Dikonversi dari kolom teks JSON di DB ke array PHP
        'created_at' => 'datetime',
    ];

    // Relasi Many-to-One: Setiap rekam jejak (Activity) terikat pada 1 User (Pelaku tindakan)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
