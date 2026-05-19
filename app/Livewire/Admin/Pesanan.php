<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use App\Livewire\Traits\OwnerAccess;
use App\Models\Pesanan as PesananModel;
use App\Models\Pemasukan;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.app')]
class Pesanan extends Component
{
    use OwnerAccess;

    public function mount(string $owner = ''): void
    {
        $user = Auth::user();
        $username = strtolower($user->username ?? '');

        // Only admin can access here
        if ($username !== 'admin') {
            abort(403, 'Unauthorized access.');
        }
        
        $this->readonly = false;
    }
    protected function getUserId(): int
    {
        return (int) Auth::id();
    }

    public $showForm = false;
    public $editingId = null;

    public $nomor = '';
    public $nama = '';
    public $tipe = '';
    public $jumlah = '';
    public $alamat_penjemputan = '';
    public $alamat_pengiriman = '';
    public $status = 'pending';
    public $description = '';
    public $produk_id = null;
    public $no_whatsapp = '';

    protected $rules = [
        'nomor' => 'required|string|unique:pesanans,nomor',
        'nama' => 'required|string|min:3',
        'tipe' => 'required|string',
        'jumlah' => 'required|integer|min:1',
        'alamat_penjemputan' => 'required|string|min:5',
        'alamat_pengiriman' => 'required|string|min:5',
        'status' => 'required|in:pending,accepted,rejected,delivered',
        'description' => 'nullable|string',
        'produk_id' => 'required|exists:produks,id',
        'no_whatsapp' => 'required|string|min:10',
    ];

    #[Computed(cache: true)]
    public function produks()
    {
        return \App\Models\Produk::select('id', 'nama')->get();
    }

    public function getPesanansProperty()
    {
        return PesananModel::with(['user', 'produk'])
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->latest()
            ->get();
    }

    private function invalidatePesananCache()
    {
        $this->dispatch('DISPATCH_INVALIDATE_PESANAN');
    }

    public function toggleForm()
    {
        $this->showForm = !$this->showForm;
        if (!$this->showForm) {
            $this->resetForm();
        }
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->resetAttributes();
        $this->editingId = null;
        $this->resetValidation();
    }

    private function resetAttributes()
    {
        $this->nomor = '';
        $this->nama = '';
        $this->tipe = '';
        $this->jumlah = '';
        $this->alamat_penjemputan = '';
        $this->alamat_pengiriman = '';
        $this->status = 'pending';
        $this->description = '';
        $this->produk_id = null;
        $this->no_whatsapp = '';
    }

    public function editPesanan($id)
    {
        $pesanan = PesananModel::select(['id', 'nomor', 'nama', 'tipe', 'jumlah', 'alamat_penjemputan', 'alamat_pengiriman', 'status', 'description', 'produk_id', 'no_whatsapp'])->findOrFail($id);
        $this->editingId = $id;
        $this->nomor = $pesanan->nomor;
        $this->nama = $pesanan->nama;
        $this->tipe = $pesanan->tipe;
        $this->jumlah = $pesanan->jumlah;
        $this->alamat_penjemputan = $pesanan->alamat_penjemputan;
        $this->alamat_pengiriman = $pesanan->alamat_pengiriman;
        $this->status = $pesanan->status;
        $this->description = $pesanan->description;
        $this->produk_id = $pesanan->produk_id;
        $this->no_whatsapp = $pesanan->no_whatsapp;
        $this->showForm = true;
    }

    public function tambahPesanan()
    {
        $this->validate();

        $produk = \App\Models\Produk::findOrFail($this->produk_id);
        
        $data = [
            'nomor' => $this->nomor,
            'nama' => $this->nama,
            'tipe' => $this->tipe,
            'jumlah' => $this->jumlah,
            'alamat_penjemputan' => $this->alamat_penjemputan,
            'alamat_pengiriman' => $this->alamat_pengiriman,
            'status' => $this->status,
            'description' => $this->description,
            'produk_id' => $this->produk_id,
            'no_whatsapp' => $this->no_whatsapp,
            'harga' => $produk->harga,
            'total_harga' => $this->jumlah * $produk->harga,
        ];

        if ($this->editingId) {
            $pesanan = PesananModel::findOrFail($this->editingId);
            $pesanan->update($data);
            $msg = 'Pesanan berhasil diperbarui!';
            $action = 'update';
        } else {
            $data['user_id'] = $this->getUserId();
            $pesanan = PesananModel::create($data);
            
            $msg = 'Pesanan berhasil ditambahkan!';
            $action = 'create';
        }

        Activity::create([
            'user_id' => $this->getUserId(),
            'action' => $action,
            'entity_type' => 'Pesanan',
            'entity_id' => $pesanan->id,
            'description' => ($action === 'update' ? 'Update: ' : 'Tambah: ') . $pesanan->nomor,
        ]);

        $this->invalidatePesananCache();
        session()->flash('success', $msg);
        $this->closeForm();
    }

    public function acceptPesanan($id)
    {
        $pesanan = PesananModel::select(['id', 'nomor', 'status'])->findOrFail($id);
        if ($pesanan->status === 'accepted') return;
        
        $pesanan->update(['status' => 'accepted']);

        Activity::create([
            'user_id' => $this->getUserId(),
            'action' => 'accept',
            'entity_type' => 'Pesanan',
            'entity_id' => $pesanan->id,
            'description' => "Terima: #{$pesanan->nomor}",
        ]);
        
        $this->invalidatePesananCache();
        session()->flash('success', 'Pesanan diterima!');
    }

    public function rejectPesanan($id)
    {
        $pesanan = PesananModel::select(['id', 'nomor', 'status'])->findOrFail($id);
        if ($pesanan->status === 'rejected') return;
        
        $pesanan->update(['status' => 'rejected']);

        Activity::create([
            'user_id' => $this->getUserId(),
            'action' => 'reject',
            'entity_type' => 'Pesanan',
            'entity_id' => $pesanan->id,
            'description' => "Tolak: #{$pesanan->nomor}",
        ]);
        
        $this->invalidatePesananCache();
        session()->flash('success', 'Pesanan ditolak!');
    }

    public function markDelivered($id)
    {
        $pesanan = PesananModel::with('produk')->findOrFail($id);
        if ($pesanan->status === 'delivered') return;
        
        $pesanan->update(['status' => 'delivered']);

        // Calculate total price: quantity * product price
        $totalHarga = 0;
        if ($pesanan->produk) {
            $totalHarga = $pesanan->jumlah * $pesanan->produk->harga;
        }

        // Create Pemasukan entry automatically
        Pemasukan::create([
            'tanggal' => today(),
            'jumlah' => $totalHarga,
            'keterangan' => "Penjualan: {$pesanan->nomor} ({$pesanan->nama})",
            'kategori' => 'penjualan',
            'status' => 'selesai',
            'user_id' => $this->getUserId(),
        ]);

        Activity::create([
            'user_id' => $this->getUserId(),
            'action' => 'update',
            'entity_type' => 'Pesanan',
            'entity_id' => $pesanan->id,
            'description' => "Selesai/Terkirim: #{$pesanan->nomor}",
        ]);
        
        $this->invalidatePesananCache();
        session()->flash('success', 'Pesanan ditandai terkirim & data pemasukan dicatat!');
    }

    public function deletePesanan($id)
    {
        $pesanan = PesananModel::select(['id', 'nomor'])->findOrFail($id);
        $pesanan->delete();

        Activity::create([
            'user_id' => $this->getUserId(),
            'action' => 'delete',
            'entity_type' => 'Pesanan',
            'entity_id' => $id,
            'description' => "Hapus: #{$pesanan->nomor}",
        ]);
        
        $this->invalidatePesananCache();
        session()->flash('success', 'Pesanan berhasil dihapus!');
    }

    public function render()
    {
        return view('livewire.admin.pesanan');
    }
}