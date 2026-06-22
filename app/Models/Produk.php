<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Model Produk merepresentasikan tabel 'produks' di database.
// Berfungsi untuk mengelola katalog produk/layanan yang ditawarkan oleh aplikasi.
class Produk extends Model
{
    // $fillable adalah daftar kolom yang diizinkan untuk diisi data secara massal (Mass Assignment Protection)
    protected $fillable = [
        'nama',      // Nama produk atau layanan
        'jenis',     // Kategori jenis produk
        'harga',     // Harga produk (tipe integer/decimal)
        'satuan',    // Satuan pengukuran produk (misal: unit, kg, pax)
        'deskripsi', // Keterangan detail mengenai produk
        'gambar'     // Path penyimpanan file gambar produk (disimpan di disk storage)
    ];
}