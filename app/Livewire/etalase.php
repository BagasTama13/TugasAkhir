<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
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
    use WithFileUploads;

    protected function getUserId(): int
    {
        return (int) Auth::id();
    }

    public $nama, $jenis, $harga, $satuan, $deskripsi, $gambar;
    public $showForm = false;
    public $editingId = null;

    protected $rules = [
        'nama' => 'required',
        'jenis' => 'required',
        'harga' => 'required|numeric',
        'satuan' => 'required',
        'gambar' => 'nullable|image|max:2048',
    ];

    #[Computed]
    public function produk()
    {
        return Produk::latest()->get();
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
        $produk = Produk::find($id);
        if ($produk) {
            $produkNama = $produk->nama;
            
            if ($produk->gambar) {
                Storage::disk('public')->delete($produk->gambar);
            }
            
            $produk->delete();
            
            Activity::create([
                'user_id' => $this->getUserId(),
                'action' => 'delete',
                'entity_type' => 'Produk',
                'entity_id' => $id,
                'description' => "Menghapus produk: {$produkNama}",
            ]);
            
            session()->flash('success', 'Produk berhasil dihapus!');
        }
    }

    public function tambahProduk()
    {
        $this->validate();

        if ($this->editingId) {
            // UPDATE MODE
            $produk = Produk::find($this->editingId);
            if ($produk) {
                $oldValues = [
                    'nama' => $produk->nama,
                    'jenis' => $produk->jenis,
                    'harga' => $produk->harga,
                    'satuan' => $produk->satuan,
                    'deskripsi' => $produk->deskripsi,
                ];
                
                $path = $produk->gambar;

                if ($this->gambar) {
                    if ($produk->gambar) {
                        Storage::disk('public')->delete($produk->gambar);
                    }
                    $path = $this->gambar->store('produk', 'public');
                }

                $produk->update([
                    'nama' => $this->nama,
                    'jenis' => $this->jenis,
                    'harga' => $this->harga,
                    'satuan' => $this->satuan,
                    'deskripsi' => $this->deskripsi,
                    'gambar' => $path,
                ]);

                Activity::create([
                    'user_id' => $this->getUserId(),
                    'action' => 'update',
                    'entity_type' => 'Produk',
                    'entity_id' => $this->editingId,
                    'description' => "Memperbarui produk: {$this->nama}",
                    'old_values' => $oldValues,
                    'new_values' => [
                        'nama' => $this->nama,
                        'jenis' => $this->jenis,
                        'harga' => $this->harga,
                        'satuan' => $this->satuan,
                        'deskripsi' => $this->deskripsi,
                    ]
                ]);

                session()->flash('success', 'Produk berhasil diperbarui!');
            }
        } else {
            // CREATE MODE
            $path = null;

            if ($this->gambar) {
                $path = $this->gambar->store('produk', 'public');
                Log::info('File stored at: ' . $path);
            }

            $produk = Produk::create([
                'nama' => $this->nama,
                'jenis' => $this->jenis,
                'harga' => $this->harga,
                'satuan' => $this->satuan,
                'deskripsi' => $this->deskripsi,
                'gambar' => $path,
            ]);

            Log::info('Produk created: ' . $produk->id . ' with gambar: ' . $path);
            
            Activity::create([
                'user_id' => $this->getUserId(),
                'action' => 'create',
                'entity_type' => 'Produk',
                'entity_id' => $produk->id,
                'description' => "Menambahkan produk baru: {$this->nama}",
                'new_values' => [
                    'nama' => $this->nama,
                    'jenis' => $this->jenis,
                    'harga' => $this->harga,
                    'satuan' => $this->satuan,
                    'deskripsi' => $this->deskripsi,
                ]
            ]);
            
            session()->flash('success', 'Produk berhasil ditambahkan!');
        }

        $this->closeForm();
    }

    public function render()
    {
        return view('livewire.etalase');
    }
}

