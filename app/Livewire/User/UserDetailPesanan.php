<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;
use App\Models\Produk;
use App\Models\Pesanan;
use App\Models\Activity;

#[Layout('layouts.user')]
class UserDetailPesanan extends Component
{
    public $nama_pembeli = '';
    public $selectedProdukId = null;
    public $jumlah = '';
    public $alamat = '';
    public $catatan = '';
    public $no_whatsapp = '';
    public $latitude = '';
    public $longitude = '';
    public $jarak = 0;
    public $ongkos_kirim = 0;

    protected $rules = [
        'nama_pembeli' => 'required|string|min:3',
        'selectedProdukId' => 'required|exists:produks,id',
        'jumlah' => 'required|integer|min:1',
        'alamat' => 'required|string|min:5',
        'no_whatsapp' => 'required|string|min:10',
        'catatan' => 'nullable|string',
    ];

    protected $messages = [
        'nama_pembeli.required' => 'Nama pembeli wajib diisi.',
        'selectedProdukId.required' => 'Pilih produk terlebih dahulu.',
        'jumlah.required' => 'Jumlah produk wajib diisi.',
        'jumlah.min' => 'Jumlah produk minimal 1.',
        'alamat.required' => 'Alamat wajib diisi.',
        'no_whatsapp.required' => 'No. WhatsApp wajib diisi.',
    ];

    public $productCategory = '';

    public function mount(): void
    {
        $user = Auth::user();

        $this->nama_pembeli = $user->name;
        $this->no_whatsapp = $user->no_hp ?? '';
        $this->alamat = $user->alamat ?? '';
        $this->latitude = $user->latitude ?? '';
        $this->longitude = $user->longitude ?? '';

        // Pre-select product if passed via query parameter
        $produkId = request()->query('produk');
        if ($produkId) {
            $p = Produk::find($produkId);
            if ($p) {
                $this->selectedProdukId = (int) $produkId;
                $this->productCategory = $p->nama; // Keep original case for DB query
            }
        }
    }

    #[Computed]
    public function produks()
    {
        if (!$this->productCategory) {
            return collect(); // Don't show anything if no category is selected
        }

        return Produk::where('nama', $this->productCategory)->get();
    }

    #[Computed]
    public function selectedProduk()
    {
        if ($this->selectedProdukId) {
            return Produk::find($this->selectedProdukId);
        }
        return null;
    }



    public function selectProduk($id)
    {
        $this->selectedProdukId = (int) $id;
    }

    public function updatedJarak(): void
    {
        $this->calculateShippingFee();
    }

    // Jarak tetap dihitung untuk info, tapi ongkos kirim dihilangkan
    public function calculateShippingFee(): void
    {
        $this->ongkos_kirim = 0;
    }

    /**
     * Logika Inti 'Checkout' Pesanan:
     * - Memvalidasi keranjang belanja & menghitung total biaya (termasuk ongkir)
     * - Mengenerate nomor resi/nomor pesanan (Contoh: USR-X8A9B)
     * - Menyimpan transaksi ke tabel Pesanans dengan status awal 'pending'
     * - Merekam jejak aktivitas ke dalam tabel Activity Log
     */
    public function kirimPesanan()
    {
        // 1. Verifikasi (Backend Validation) & Kalkulasi Ongkir
        $this->validate();
        $this->calculateShippingFee();

        $produk = Produk::findOrFail($this->selectedProdukId);

        // 2. Generator Unique ID untuk Nomor Pesanan (Format: USR-***)
        $nomor = 'USR-' . strtoupper(uniqid());

        // Satu titik pengiriman untuk semua tipe produk
        $dbAlamatPenjemputan = $this->latitude && $this->longitude
            ? "{$this->latitude},{$this->longitude}"
            : '-';

        // 3. Menyimpan Transaksi (Create Record Database)
        $pesanan = Pesanan::create([
            'nomor'              => $nomor,
            'nama'               => $this->nama_pembeli,
            'tipe'               => $produk->jenis,
            'jumlah'             => $this->jumlah,
            'alamat_penjemputan' => $dbAlamatPenjemputan,
            'alamat_pengiriman'  => $this->alamat,
            'status'             => 'pending',
            'description'        => "Produk: {$produk->nama} ({$produk->jenis}) | Jarak: {$this->jarak} km",
            'user_id'            => Auth::id(),
            'produk_id'          => $produk->id,
            'harga'              => $produk->harga,
            'ongkos_kirim'       => 0,
            'jarak'              => $this->jarak,
            'total_harga'        => $this->jumlah * $produk->harga,
            'catatan'            => $this->catatan,
            'no_whatsapp'        => $this->no_whatsapp,
        ]);

        // 4. Mencatat Riwayat Audit (Activity Trailing)
        Activity::create([
            'user_id' => Auth::id(),
            'action' => 'create',
            'entity_type' => 'Pesanan',
            'entity_id' => $pesanan->id,
            'description' => "User order: #{$nomor} - {$produk->nama}",
        ]);

        // 5. Menyimpan pesan sukses dan me-redirect antarmuka pengguna ke halaman riwayat pesanannya
        session()->flash('success', 'Pesanan berhasil dikirim! Nomor pesanan: ' . $nomor);
        return redirect()->route('user.pesanan');
    }

    public function render()
    {
        return view('livewire.user.detail-pesanan');
    }
}
