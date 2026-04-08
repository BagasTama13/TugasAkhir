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

        // If owner parameter passed, this is for owner panel - reject
        if (!empty($owner)) {
            abort(403, 'Invalid access. Use owner panel instead.');
        }

        // Block owner and worker users from admin panel
        if (in_array($username, ['owner', 'worker'], true)) {
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

    protected $rules = [
        'nomor' => 'required|string|unique:pesanans,nomor',
        'nama' => 'required|string|min:3',
        'tipe' => 'required|string',
        'jumlah' => 'required|integer|min:1',
        'alamat_penjemputan' => 'required|string|min:5',
        'alamat_pengiriman' => 'required|string|min:5',
        'status' => 'required|in:pending,accepted,rejected,delivered',
        'description' => 'nullable|string',
    ];

    #[Computed(cache: true)]
    public function pesanans()
    {
        return PesananModel::select(['id', 'nomor', 'nama', 'tipe', 'jumlah', 'alamat_penjemputan', 'alamat_pengiriman', 'status', 'created_at'])
            ->with('user:id,name,username')
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
    }

    public function editPesanan($id)
    {
        $pesanan = PesananModel::select(['id', 'nomor', 'nama', 'tipe', 'jumlah', 'alamat_penjemputan', 'alamat_pengiriman', 'status', 'description'])->findOrFail($id);
        $this->editingId = $id;
        $this->nomor = $pesanan->nomor;
        $this->nama = $pesanan->nama;
        $this->tipe = $pesanan->tipe;
        $this->jumlah = $pesanan->jumlah;
        $this->alamat_penjemputan = $pesanan->alamat_penjemputan;
        $this->alamat_pengiriman = $pesanan->alamat_pengiriman;
        $this->status = $pesanan->status;
        $this->description = $pesanan->description;
        $this->showForm = true;
    }

    public function tambahPesanan()
    {
        $this->validate();

        $data = [
            'nomor' => $this->nomor,
            'nama' => $this->nama,
            'tipe' => $this->tipe,
            'jumlah' => $this->jumlah,
            'alamat_penjemputan' => $this->alamat_penjemputan,
            'alamat_pengiriman' => $this->alamat_pengiriman,
            'status' => $this->status,
            'description' => $this->description,
        ];

        if ($this->editingId) {
            $pesanan = PesananModel::findOrFail($this->editingId);
            $pesanan->update($data);
            $msg = 'Pesanan berhasil diperbarui!';
            $action = 'update';
        } else {
            $data['user_id'] = $this->getUserId();
            $pesanan = PesananModel::create($data);
            
            // Create Pemasukan entry
            Pemasukan::create([
                'tanggal' => today(),
                'jumlah' => $this->jumlah,
                'keterangan' => "Pesanan #{$pesanan->nomor}",
                'kategori' => 'penjualan',
                'status' => 'pending',
                'user_id' => $this->getUserId(),
            ]);
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