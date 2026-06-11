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
            'nomor'              => 'required|string|unique:pesanans,nomor,' . ($this->editingId ?? 'NULL') . ',id',
            'nama'               => 'required|string|min:3',
            'tipe'               => 'required|string',
            'jumlah'             => 'required|integer|min:1',
            'alamat_penjemputan' => 'required|string',
            'alamat_pengiriman'  => 'required|string|min:5',
            'status'             => 'required|in:pending,dalam_antrian,diproses,terkirim,rejected',
            'description'        => 'nullable|string',
            'durasi'             => 'required_if:tipe,sewa|integer|min:1',
            'no_whatsapp'        => 'required|string|min:10',
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
        $this->description = '';
        $this->durasi = '';
        $this->produk_id = null;
        $this->no_whatsapp = '';
    }

    public function editPesanan($id)
    {
        $pesanan = PesananModel::select([
            'id', 'nomor', 'nama', 'tipe', 'jumlah',
            'alamat_penjemputan', 'alamat_pengiriman', 'status',
            'description', 'produk_id', 'no_whatsapp', 'durasi',
        ])->findOrFail($id);

        $this->editingId        = $id;
        $this->nomor            = $pesanan->nomor;
        $this->nama             = $pesanan->nama;
        $this->tipe             = $pesanan->tipe;
        $this->jumlah           = $pesanan->jumlah;
        $this->alamat_penjemputan = $pesanan->alamat_penjemputan;
        $this->alamat_pengiriman  = $pesanan->alamat_pengiriman;
        $this->durasi           = $pesanan->durasi;
        $this->description      = $pesanan->description;
        $this->produk_id        = $pesanan->produk_id;
        $this->no_whatsapp      = $pesanan->no_whatsapp;
        $this->showForm = true;
    }

    public function tambahPesanan()
    {
        $this->validate();

        $produk = \App\Models\Produk::findOrFail($this->produk_id);

        $data = [
            'nomor'              => $this->nomor,
            'nama'               => $this->nama,
            'tipe'               => $this->tipe,
            'jumlah'             => $this->jumlah,
            'alamat_penjemputan' => $this->alamat_penjemputan,
            'alamat_pengiriman'  => $this->alamat_pengiriman,
            'status'             => $this->status,
            'description'        => $this->description,
            'produk_id'          => $this->produk_id,
            'no_whatsapp'        => $this->no_whatsapp,
            'durasi'             => $this->durasi,
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
            $msg    = 'Pesanan berhasil diperbarui!';
            $action = 'update';
        } else {
            $data['user_id'] = $this->getUserId();
            $pesanan = PesananModel::create($data);
            $msg    = 'Pesanan berhasil ditambahkan!';
            $action = 'create';
        }

        Activity::create([
            'user_id'     => $this->getUserId(),
            'action'      => $action,
            'entity_type' => 'Pesanan',
            'entity_id'   => $pesanan->id,
            'description' => ($action === 'update' ? 'Update: ' : 'Tambah: ') . $pesanan->nomor,
        ]);

        $this->invalidatePesananCache();
        session()->flash('success', $msg);
        $this->closeForm();
    }

    /**
     * Admin mengkonfirmasi pesanan:
     * - status: pending → dalam_antrian
     * - payment_status: → belum_dibayar
     * - Buat Pemasukan record (status pending) agar masuk "Pemasukan Pending"
     * - Pesanan tampil di panel worker
     */
    public function acceptPesanan($id)
    {
        $pesanan = PesananModel::with('produk')->findOrFail($id);
        if ($pesanan->status !== 'pending') return;

        // Calculate total
        $totalHarga = $this->hitungTotalHarga($pesanan);

        $pesanan->update([
            'status'         => 'dalam_antrian',
            'payment_status' => 'belum_dibayar',
            'total_harga'    => $totalHarga,
        ]);

        // Create Pemasukan as pending (will show in "Pemasukan Pending")
        Pemasukan::updateOrCreate(
            ['pesanan_id' => $pesanan->id],
            [
                'tanggal'    => today(),
                'jumlah'     => $totalHarga,
                'keterangan' => "Penjualan: {$pesanan->nomor} ({$pesanan->nama})",
                'kategori'   => 'penjualan',
                'status'     => 'pending',
                'user_id'    => $this->getUserId(),
            ]
        );

        Activity::create([
            'user_id'     => $this->getUserId(),
            'action'      => 'accept',
            'entity_type' => 'Pesanan',
            'entity_id'   => $pesanan->id,
            'description' => "Konfirmasi pesanan: #{$pesanan->nomor}",
        ]);

        $this->invalidatePesananCache();
        session()->flash('success', 'Pesanan dikonfirmasi & masuk antrian!');
    }

    public function rejectPesanan($id)
    {
        $pesanan = PesananModel::findOrFail($id);
        if ($pesanan->status === 'rejected') return;

        $pesanan->update(['status' => 'rejected']);

        Activity::create([
            'user_id'     => $this->getUserId(),
            'action'      => 'reject',
            'entity_type' => 'Pesanan',
            'entity_id'   => $pesanan->id,
            'description' => "Tolak: #{$pesanan->nomor}",
        ]);

        $this->invalidatePesananCache();
        session()->flash('success', 'Pesanan ditolak!');
    }

    /**
     * Admin mengkonfirmasi pembayaran manual (Flow 4 - bayar di kantor).
     */
    public function konfirmasiPembayaranAdmin($id)
    {
        $pesanan = PesananModel::findOrFail($id);
        if ($pesanan->payment_status === 'telah_dibayar') return;

        $pesanan->update([
            'payment_status' => 'telah_dibayar',
            'paid_at'        => now(),
        ]);

        Pemasukan::updateOrCreate(
            ['pesanan_id' => $pesanan->id],
            [
                'tanggal'    => today(),
                'jumlah'     => $pesanan->total_harga,
                'keterangan' => "Pembayaran Kantor: {$pesanan->nomor} ({$pesanan->nama})",
                'kategori'   => 'penjualan',
                'status'     => 'confirmed',
                'user_id'    => $this->getUserId(),
            ]
        );

        Activity::create([
            'user_id'     => $this->getUserId(),
            'action'      => 'payment',
            'entity_type' => 'Pesanan',
            'entity_id'   => $pesanan->id,
            'description' => "Konfirmasi bayar kantor: #{$pesanan->nomor}",
        ]);

        $this->invalidatePesananCache();
        session()->flash('success', 'Pembayaran dikonfirmasi!');
    }

    private function hitungTotalHarga(PesananModel $pesanan): float|int
    {
        if ($pesanan->tipe === 'carter') {
            return $this->calculateCarterPrice($pesanan);
        } elseif ($pesanan->tipe === 'sewa') {
            return ($pesanan->durasi ?? 1) * config('pricing.sewa_per_day', 300000);
        }
        return $pesanan->jumlah * ($pesanan->produk->harga ?? 0);
    }

    private function calculateCarterPrice($pesanan)
    {
        $distanceKm = $this->getDistanceInKm($pesanan->alamat_penjemputan, $pesanan->alamat_pengiriman);
        $basePrice  = 100000;
        if ($distanceKm <= 20) {
            return $basePrice;
        }
        $extraKm = $distanceKm - 20;
        return $basePrice + ($extraKm * 20000);
    }

    private function getDistanceInKm($origin, $destination)
    {
        $originParts = explode(',', $origin);
        $destParts   = explode(',', $destination);
        if (count($originParts) < 2 || count($destParts) < 2) return 0;
        [$lat1, $lon1] = array_map('floatval', $originParts);
        [$lat2, $lon2] = array_map('floatval', $destParts);
        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2)
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }

    public function deletePesanan($id)
    {
        $pesanan = PesananModel::findOrFail($id);
        $pesanan->delete();

        Activity::create([
            'user_id'     => $this->getUserId(),
            'action'      => 'delete',
            'entity_type' => 'Pesanan',
            'entity_id'   => $id,
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