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
    public function pesanan()
    {
        return PesananModel::select(['id', 'user_id', 'produk_id', 'jumlah', 'harga_total', 'status', 'created_at'])
            ->with('user:id,name,username')
            ->latest()
            ->limit(100)
            ->get();
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
        $pesanan = PesananModel::findOrFail($id);
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

        if ($this->editingId) {
            $pesanan = PesananModel::findOrFail($this->editingId);
            $oldValues = $pesanan->toArray();

            $pesanan->update([
                'nomor' => $this->nomor,
                'nama' => $this->nama,
                'tipe' => $this->tipe,
                'jumlah' => $this->jumlah,
                'alamat_penjemputan' => $this->alamat_penjemputan,
                'alamat_pengiriman' => $this->alamat_pengiriman,
                'status' => $this->status,
                'description' => $this->description,
            ]);

            Activity::create([
                'user_id' => $this->getUserId(),
                'action' => 'update',
                'entity_type' => 'Pesanan',
                'entity_id' => $pesanan->id,
                'description' => "Mengubah pesanan #{$pesanan->nomor}",
                'old_values' => $oldValues,
                'new_values' => $pesanan->fresh()->toArray(),
            ]);
        } else {
            $pesanan = PesananModel::create([
                'nomor' => $this->nomor,
                'nama' => $this->nama,
                'tipe' => $this->tipe,
                'jumlah' => $this->jumlah,
                'alamat_penjemputan' => $this->alamat_penjemputan,
                'alamat_pengiriman' => $this->alamat_pengiriman,
                'status' => $this->status,
                'description' => $this->description,
                'user_id' => $this->getUserId(),
            ]);

            // Create Pemasukan entry as pending when pesanan is created
            Pemasukan::create([
                'tanggal' => today(),
                'jumlah' => $this->jumlah,
                'keterangan' => "Pesanan #{$pesanan->nomor} dari {$pesanan->nama}",
                'kategori' => 'penjualan',
                'status' => 'pending',
                'catatan' => "Dari pesanan: {$pesanan->tipe}",
                'user_id' => $this->getUserId(),
            ]);

            Activity::create([
                'user_id' => $this->getUserId(),
                'action' => 'create',
                'entity_type' => 'Pesanan',
                'entity_id' => $pesanan->id,
                'description' => "Menambah pesanan #{$pesanan->nomor}",
                'old_values' => [],
                'new_values' => $pesanan->toArray(),
            ]);
        }

        $this->closeForm();
    }

    public function acceptPesanan($id)
    {
        $pesanan = PesananModel::findOrFail($id);
        $oldStatus = $pesanan->status;

        $pesanan->update(['status' => 'accepted']);

        // Find and confirm the corresponding Pemasukan
        $pemasukan = Pemasukan::where('keterangan', 'like', "%Pesanan #{$pesanan->nomor}%")->first();
        if ($pemasukan) {
            $pemasukan->update(['status' => 'confirmed']);
        }

        Activity::create([
            'user_id' => $this->getUserId(),
            'action' => 'accept',
            'entity_type' => 'Pesanan',
            'entity_id' => $pesanan->id,
            'description' => "Menerima pesanan #{$pesanan->nomor} dari {$pesanan->nama}",
            'old_values' => ['status' => $oldStatus],
            'new_values' => ['status' => 'accepted'],
        ]);
    }

    public function rejectPesanan($id)
    {
        $pesanan = PesananModel::findOrFail($id);
        $oldStatus = $pesanan->status;

        $pesanan->update(['status' => 'rejected']);

        // Find and reject the corresponding Pemasukan
        $pemasukan = Pemasukan::where('keterangan', 'like', "%Pesanan #{$pesanan->nomor}%")->first();
        if ($pemasukan) {
            $pemasukan->update(['status' => 'rejected']);
        }

        Activity::create([
            'user_id' => $this->getUserId(),
            'action' => 'reject',
            'entity_type' => 'Pesanan',
            'entity_id' => $pesanan->id,
            'description' => "Menolak pesanan #{$pesanan->nomor} dari {$pesanan->nama}",
            'old_values' => ['status' => $oldStatus],
            'new_values' => ['status' => 'rejected'],
        ]);
    }

    public function markDelivered($id)
    {
        $pesanan = PesananModel::findOrFail($id);
        $oldStatus = $pesanan->status;

        $pesanan->update(['status' => 'delivered']);

        Activity::create([
            'user_id' => $this->getUserId(),
            'action' => 'update',
            'entity_type' => 'Pesanan',
            'entity_id' => $pesanan->id,
            'description' => "Menandai pesanan #{$pesanan->nomor} sebagai terkirim",
            'old_values' => ['status' => $oldStatus],
            'new_values' => ['status' => 'delivered'],
        ]);
    }

    public function deletePesanan($id)
    {
        $pesanan = PesananModel::findOrFail($id);
        $nomor = $pesanan->nomor;

        // Delete corresponding Pemasukan
        Pemasukan::where('keterangan', 'like', "%Pesanan #{$nomor}%")->delete();

        $pesanan->delete();

        Activity::create([
            'user_id' => $this->getUserId(),
            'action' => 'delete',
            'entity_type' => 'Pesanan',
            'entity_id' => $id,
            'description' => "Menghapus pesanan #{$nomor}",
            'old_values' => [],
            'new_values' => [],
        ]);
    }

    public function render()
    {
        return view('livewire.admin.pesanan');
    }
}