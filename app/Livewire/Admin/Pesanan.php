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
    public $durasi = '';
    public $produk_id = null;
    public $no_whatsapp = '';

    public function rules()
    {
        return [
            'nomor' => 'required|string|unique:pesanans,nomor,' . ($this->editingId ?? 'NULL') . ',id',
            'nama' => 'required|string|min:3',
            'tipe' => 'required|string',
            'jumlah' => 'required|integer|min:1',
            'alamat_penjemputan' => 'required|string',
            'alamat_pengiriman' => 'required|string|min:5',
            'status' => 'required|in:pending,accepted,rejected,perlu_dibayar,terbayar',
            'description' => 'nullable|string',
            'durasi' => 'required_if:tipe,sewa|integer|min:1',
            'no_whatsapp' => 'required|string|min:10',
        ];
    }

    #[Computed(cache: true)]
    public function produks()
    {
        return \App\Models\Produk::select('id', 'nama', 'harga')->get();
    }

    #[Computed]
    public function pesanans()
    {
        $query = PesananModel::with(['user', 'produk']);

        $username = strtolower(Auth::user()->username ?? '');
        if (str_starts_with($username, 'worker')) {
            $query->whereIn('status', ['accepted', 'perlu_dibayar', 'terbayar']);
        }

        return $query->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
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
        $this->description = '';
        $this->durasi = '';
        $this->produk_id = null;
        $this->no_whatsapp = '';
    }

    public function editPesanan($id)
    {
        $pesanan = PesananModel::select(['id', 'nomor', 'nama', 'tipe', 'jumlah', 'alamat_penjemputan', 'alamat_pengiriman', 'status', 'description', 'produk_id', 'no_whatsapp', 'durasi'])->findOrFail($id);
        $this->editingId = $id;
        $this->nomor = $pesanan->nomor;
        $this->nama = $pesanan->nama;
        $this->tipe = $pesanan->tipe;
        $this->jumlah = $pesanan->jumlah;
        $this->alamat_penjemputan = $pesanan->alamat_penjemputan;
        $this->alamat_pengiriman = $pesanan->alamat_pengiriman;
        $this->durasi = $pesanan->durasi;
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
            'durasi' => $this->durasi,
        ];

        if ($this->tipe === 'carter') {
            $totalHarga = $this->calculateCarterPrice($this);
        } elseif ($this->tipe === 'sewa') {
            $totalHarga = $this->durasi * config('pricing.sewa_per_day', 300000);
        } else {
            $totalHarga = $this->jumlah * $produk->harga;
        }
        $data['total_harga'] = $totalHarga;

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
        $pesanan = PesananModel::findOrFail($id);
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
        $pesanan = PesananModel::findOrFail($id);
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

    public function markPerluDibayar($id)
    {
        $pesanan = PesananModel::with('produk')->findOrFail($id);
        if ($pesanan->status === 'perlu_dibayar') return;
        
        $pesanan->update(['status' => 'perlu_dibayar']);

        // Calculate total price based on tipe
        if ($pesanan->tipe === 'carter') {
            $totalHarga = $this->calculateCarterPrice($pesanan);
        } elseif ($pesanan->tipe === 'sewa') {
            $totalHarga = $pesanan->durasi * config('pricing.sewa_per_day', 300000);
        } else {
            $totalHarga = $pesanan->jumlah * ($pesanan->produk->harga ?? 0);
        }

        // We create or update the Pemasukan record as 'pending'
        Pemasukan::updateOrCreate(
            ['pesanan_id' => $pesanan->id],
            [
                'tanggal' => today(),
                'jumlah' => $totalHarga,
                'keterangan' => "Penjualan: {$pesanan->nomor} ({$pesanan->nama})",
                'kategori' => 'penjualan',
                'status' => 'pending',
                'user_id' => $this->getUserId(),
            ]
        );

        Activity::create([
            'user_id' => $this->getUserId(),
            'action' => 'update',
            'entity_type' => 'Pesanan',
            'entity_id' => $pesanan->id,
            'description' => "Kirim/Perlu dibayar: #{$pesanan->nomor}",
        ]);
        
        $this->invalidatePesananCache();
        session()->flash('success', 'Pesanan ditandai terkirim (perlu dibayar)!');
    }

    public function markTerbayar($id)
    {
        $pesanan = PesananModel::with('produk')->findOrFail($id);
        if ($pesanan->status === 'terbayar') return;
        
        $pesanan->update(['status' => 'terbayar']);

        // Calculate total price based on tipe
        if ($pesanan->tipe === 'carter') {
            $totalHarga = $this->calculateCarterPrice($pesanan);
        } elseif ($pesanan->tipe === 'sewa') {
            $totalHarga = $pesanan->durasi * config('pricing.sewa_per_day', 300000);
        } else {
            $totalHarga = $pesanan->jumlah * ($pesanan->produk->harga ?? 0);
        }

        // We find the existing Pemasukan or create it, then mark as 'confirmed' and update the user_id (petugas)
        $pemasukan = Pemasukan::updateOrCreate(
            ['pesanan_id' => $pesanan->id],
            [
                'tanggal' => today(),
                'jumlah' => $totalHarga,
                'keterangan' => "Penjualan: {$pesanan->nomor} ({$pesanan->nama})",
                'kategori' => 'penjualan',
                'status' => 'confirmed',
                'user_id' => $this->getUserId(),
            ]
        );

        Activity::create([
            'user_id' => $this->getUserId(),
            'action' => 'update',
            'entity_type' => 'Pesanan',
            'entity_id' => $pesanan->id,
            'description' => "Pesanan terbayar: #{$pesanan->nomor}",
        ]);
        
        $this->invalidatePesananCache();
        session()->flash('success', 'Pesanan berhasil ditandai terbayar & status pembayaran dicatat!');
    }

    private function calculateCarterPrice($pesanan)
    {
        // Placeholder distance calculation. In production, integrate with a geolocation API.
        $distanceKm = $this->getDistanceInKm($pesanan->alamat_penjemputan, $pesanan->alamat_pengiriman);
        $basePrice = 100000;
        if ($distanceKm <= 20) {
            return $basePrice;
        }
        $extraKm = $distanceKm - 20;
        return $basePrice + ($extraKm * 20000);
    }

    private function getDistanceInKm($origin, $destination)
    {
        // Simple Haversine formula assuming coordinates are provided as "lat,lon" strings.
        // For addresses, this should be replaced with a geocoding service.
        $originParts = explode(',', $origin);
        $destParts = explode(',', $destination);
        if (count($originParts) < 2 || count($destParts) < 2) return 0;
        [$lat1, $lon1] = array_map('floatval', $originParts);
        [$lat2, $lon2] = array_map('floatval', $destParts);
        $earthRadius = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return $earthRadius * $c;
    }

    public function deletePesanan($id)
    {
        $pesanan = PesananModel::findOrFail($id);
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