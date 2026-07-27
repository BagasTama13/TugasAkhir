<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Livewire\Traits\OwnerAccess;
use App\Models\Produk;
use App\Models\Activity;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

// #[Layout] mendefinisikan layout blade yang membungkus komponen ini (Master Template untuk Admin)
#[Layout('layouts.app')]
class Etalase extends Component
{
    // Menggunakan trait OwnerAccess untuk pengecekan level user, dan WithFileUploads agar Livewire bisa menangani unggahan gambar
    use OwnerAccess;
    use WithFileUploads;

    // Method mount() dipanggil saat komponen admin ini diinisialisasi pertama kali. Berisi Middleware/Pengecekan Akses (Authorization).
    public function mount(string $owner = ''): void
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        // If owner parameter passed, this is for owner panel - reject
        if (!empty($owner)) {
            abort(403, 'Invalid access. Use owner panel instead.');
        }

        // Only admin can access here
        if (!$user->hasRole('admin')) {
            abort(403, 'Unauthorized access.');
        }
    }

    protected function getUserId(): int
    {
        return (int) Auth::id();
    }

    // Variabel publik ini akan ter-binding (Data Binding) secara reaktif dengan form input di View
    public $nama, $jenis, $harga, $satuan, $deskripsi, $gambar;
    public $showForm = false;
    public $editingId = null; // Menyimpan ID produk yang sedang diedit. Jika null, berarti mode tambah produk.
    private $produkCache = null;

    // Aturan validasi (Server-side validation) sebelum menyimpan data ke database
    protected $rules = [
        'nama' => 'required',
        'jenis' => 'required',
        'harga' => 'required|numeric',
        'satuan' => 'required',
    ];

    // Method produk() menggunakan #[Computed] agar datanya di-cache selama request (mengurangi load ke database)
    #[Computed]
    public function produk()
    {
        if ($this->produkCache === null) {
            // Pengambilan data produk dari database (Read/Select)
            $this->produkCache = Produk::select(['id', 'nama', 'jenis', 'harga', 'satuan', 'gambar'])
                ->orderByDesc('id')
                ->get();
        }
        return $this->produkCache;
    }

    private function invalidateProdukCache()
    {
        $this->produkCache = null;
        \Illuminate\Support\Facades\Cache::forget('welcome_products');
    }

    // Menampilkan/menyembunyikan form di halaman Admin
    public function toggleForm()
    {
        $this->showForm = !$this->showForm;
    }

    // Menutup form dan mereset nilai inputannya
    public function closeForm()
    {
        $this->showForm = false;
        $this->editingId = null;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'nama',
            'jenis',
            'harga',
            'satuan',
            'deskripsi',
            'gambar'
        ]);
    }

    // Mengambil data spesifik produk untuk diedit dan memasukkannya ke properti form (Data Binding)
    public function editProduk($id)
    {
        $produk = Produk::find($id);
        if ($produk) {
            $this->editingId = $id;
            $this->nama = $produk->nama;
            $this->jenis = $produk->jenis;
            $this->harga = $produk->harga;
            $this->satuan = $produk->satuan;
            $this->deskripsi = $produk->deskripsi;
            $this->showForm = true;
        }
    }

    // Logika Penghapusan Data (Delete)
    public function deleteProduk($id)
    {
        $produk = Produk::select(['id', 'nama', 'gambar'])->find($id);
        if (!$produk) return;
        
        // Menghapus file gambar fisik dari local storage sebelum menghapus data dari database
        if ($produk->gambar) {
            Storage::disk('public')->delete($produk->gambar);
        }
        
        $produk->delete();
        $this->invalidateProdukCache();
        
        // Menyimpan log bahwa admin menghapus produk (Audit Trail)
        Activity::create([
            'user_id' => $this->getUserId(),
            'action' => 'delete',
            'entity_type' => 'Produk',
            'entity_id' => $id,
            'description' => "Menghapus produk: {$produk->nama}",
        ]);
        
        session()->flash('success', 'Produk berhasil dihapus!');
    }

    // Logika Penambahan dan Pembaruan Data (Create & Update)
    public function tambahProduk()
    {
        // 1. Validasi Keamanan (Security Check)
        $this->validate();
        $path = null;

        // 2. Proses Penyimpanan File Gambar (Storage)
        if ($this->gambar) {
            $path = $this->gambar->store('produk', 'public');
        }

        // Siapkan paket data untuk database
        $data = [
            'nama' => $this->nama,
            'jenis' => $this->jenis,
            'harga' => $this->harga,
            'satuan' => $this->satuan,
            'deskripsi' => $this->deskripsi,
        ];

        if ($path) {
            $data['gambar'] = $path;
        }

        // 3. Penulisan ke Database Produk (Mencetak Data Baru atau Update)
        if ($this->editingId) {
            $produk = Produk::find($this->editingId);
            // Hapus gambar lama jika admin mengunggah gambar baru
            if ($produk && $produk->gambar && $path) {
                Storage::disk('public')->delete($produk->gambar);
            }
            $produk->update($data);
            $msg = 'Produk berhasil diperbarui!';
        } else {
            $produk = Produk::create($data);
            $msg = 'Produk berhasil ditambahkan!';
        }

        // 4. Rekam Jejak Aktivitas Admin (Activity Logging)
        Activity::create([
            'user_id' => $this->getUserId(),
            'action' => $this->editingId ? 'update' : 'create',
            'entity_type' => 'Produk',
            'entity_id' => $produk->id,
            'description' => ($this->editingId ? 'Update: ' : 'Tambah: ') . $this->nama,
        ]);

        // 5. Pembersihan Cache dan Memperbarui Tampilan (Cleanup & Rendering)
        $this->invalidateProdukCache();
        session()->flash('success', $msg);
        $this->closeForm();
    }

    public function render()
    {
        return view('livewire.admin.etalase');
    }
}

