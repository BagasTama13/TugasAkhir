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

#[Layout('layouts.app')]
class Etalase extends Component
{
    use OwnerAccess;
    use WithFileUploads;

    public function mount(string $owner = ''): void
    {
        $user = Auth::user();
        $username = strtolower($user->username ?? '');

        // If owner parameter passed, this is for owner panel - reject
        if (!empty($owner)) {
            abort(403, 'Invalid access. Use owner panel instead.');
        }

        // Block owner and worker users from admin panel
        if (str_starts_with($username, 'owner') || str_starts_with($username, 'worker')) {
            abort(403, 'Access denied. Use your designated panel.');
        }

        // Only admin can access here
        if ($username !== 'admin') {
            abort(403, 'Unauthorized access.');
        }
    }

    protected function getUserId(): int
    {
        return (int) Auth::id();
    }

    public $nama, $jenis, $harga, $satuan, $deskripsi, $gambar;
    public $showForm = false;
    public $editingId = null;
    private $produkCache = null;

    protected $rules = [
        'nama' => 'required',
        'jenis' => 'required',
        'harga' => 'required|numeric',
        'satuan' => 'required',
    ];

    #[Computed]
    public function produk()
    {
        if ($this->produkCache === null) {
            $this->produkCache = Produk::select(['id', 'nama', 'jenis', 'harga', 'satuan', 'gambar'])
                ->orderByDesc('id')
                ->get();
        }
        return $this->produkCache;
    }

    private function invalidateProdukCache()
    {
        $this->produkCache = null;
    }

    public function toggleForm()
    {
        $this->showForm = !$this->showForm;
    }

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

    public function deleteProduk($id)
    {
        $produk = Produk::select(['id', 'nama', 'gambar'])->find($id);
        if (!$produk) return;
        
        if ($produk->gambar) {
            Storage::disk('public')->delete($produk->gambar);
        }
        
        $produk->delete();
        $this->invalidateProdukCache();
        
        Activity::create([
            'user_id' => $this->getUserId(),
            'action' => 'delete',
            'entity_type' => 'Produk',
            'entity_id' => $id,
            'description' => "Menghapus produk: {$produk->nama}",
        ]);
        
        session()->flash('success', 'Produk berhasil dihapus!');
    }

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

