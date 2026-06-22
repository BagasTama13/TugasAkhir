<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// Model User merepresentasikan tabel 'users' pada database.
// Digunakan untuk autentikasi sistem dan menyimpan data personal pengguna aplikasi.
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     * $fillable menentukan kolom pada tabel 'users' yang diizinkan untuk proses Create/Update secara massal.
     * @var list<string>
     */
    protected $fillable = [
        'name',       // Nama Lengkap User
        'username',   // Username unik untuk login
        'email',      // Alamat email aktif
        'password',   // Password (akan di-hash secara otomatis)
        'alamat',     // Alamat domisili
        'no_hp',      // Nomor telepon yang dapat dihubungi
        'avatar',     // Path file gambar profil
        'latitude',   // Titik koordinat peta (Latitude)
        'longitude',  // Titik koordinat peta (Longitude)
        'gmaps_link', // Tautan menuju lokasi Google Maps
    ];

    /**
     * Relasi Database: One-to-Many
     * 1 User dapat memiliki banyak Pesanan
     */
    public function pesanans()
    {
        return $this->hasMany(\App\Models\Pesanan::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     * Kolom-kolom ini akan disembunyikan saat objek User diubah menjadi JSON (keamanan data).
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token', // Token untuk fitur "Remember Me" saat login
    ];

    /**
     * Get the attributes that should be cast.
     * Mengubah tipe data secara otomatis dari tipe string database menjadi objek PHP
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime', // Diubah menjadi object Carbon DateTime
            'password' => 'hashed', // Password otomatis diproses dengan algoritma Hash Bcrypt
        ];
    }
}
