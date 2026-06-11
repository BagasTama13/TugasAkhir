<?php

namespace App\Livewire\Worker;

use App\Livewire\Admin\Pesanan;
use App\Livewire\Traits\WorkerAccess;
use App\Models\Activity;
use App\Models\Pemasukan;
use App\Models\Pesanan as PesananModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class WorkerPesanan extends Pesanan
{
    use WorkerAccess;

    public function mount(string $worker = ''): void
    {
        if (!empty($worker)) {
            $this->worker   = strtolower($worker);
            $this->readonly = true;
            $this->ensureWorkerOnly();
        }
    }

    /**
     * Worker hanya melihat pesanan yang sudah dikonfirmasi admin (bukan pending/rejected)
     */
    #[Computed]
    public function pesanans()
    {
        return PesananModel::with(['user', 'produk'])
            ->whereIn('status', ['dalam_antrian', 'diproses', 'terkirim'])
            ->latest()
            ->get();
    }

    public function render()
    {
        return view('livewire.worker.pesanan');
    }

    // Workers cannot add or edit orders
    public function tambahPesanan()
    {
        abort(403, 'Worker tidak dapat menambah pesanan.');
    }

    public function editPesanan($id)
    {
        abort(403, 'Worker tidak dapat mengedit pesanan.');
    }

    public function deletePesanan($id)
    {
        abort(403, 'Worker tidak dapat menghapus pesanan.');
    }

    public function acceptPesanan($id)
    {
        abort(403, 'Hanya admin yang dapat mengkonfirmasi pesanan.');
    }

    public function rejectPesanan($id)
    {
        abort(403, 'Hanya admin yang dapat menolak pesanan.');
    }

    /**
     * Worker memulai proses pengiriman (Flow 1, 2, 3, 4)
     * Status: dalam_antrian → diproses
     */
    public function proseskan($id)
    {
        $pesanan = PesananModel::findOrFail($id);
        if ($pesanan->status !== 'dalam_antrian') return;

        $pesanan->update(['status' => 'diproses']);

        Activity::create([
            'user_id'     => Auth::id(),
            'action'      => 'update',
            'entity_type' => 'Pesanan',
            'entity_id'   => $pesanan->id,
            'description' => "Worker memproses pesanan: #{$pesanan->nomor}",
        ]);

        session()->flash('success', 'Pesanan mulai diproses!');
    }

    /**
     * Worker mengkonfirmasi pengiriman selesai (Flow 1, 2, 3, 4)
     * Status: diproses → terkirim
     */
    public function konfirmasiKirim($id)
    {
        $pesanan = PesananModel::findOrFail($id);
        if ($pesanan->status !== 'diproses') return;

        $pesanan->update(['status' => 'terkirim']);

        Activity::create([
            'user_id'     => Auth::id(),
            'action'      => 'update',
            'entity_type' => 'Pesanan',
            'entity_id'   => $pesanan->id,
            'description' => "Pesanan terkirim: #{$pesanan->nomor}",
        ]);

        session()->flash('success', 'Pesanan dikonfirmasi terkirim!');
    }

    /**
     * Worker mengkonfirmasi pembayaran COD (Flow 3)
     * payment_status: belum_dibayar → telah_dibayar
     */
    public function konfirmasiCOD($id)
    {
        $pesanan = PesananModel::findOrFail($id);
        if ($pesanan->payment_status === 'telah_dibayar') return;

        $pesanan->update([
            'payment_status' => 'telah_dibayar',
            'paid_at'        => now(),
        ]);

        // Update Pemasukan menjadi confirmed
        Pemasukan::updateOrCreate(
            ['pesanan_id' => $pesanan->id],
            [
                'tanggal'    => today(),
                'jumlah'     => $pesanan->total_harga,
                'keterangan' => "COD: {$pesanan->nomor} ({$pesanan->nama})",
                'kategori'   => 'penjualan',
                'status'     => 'confirmed',
                'user_id'    => Auth::id(),
            ]
        );

        Activity::create([
            'user_id'     => Auth::id(),
            'action'      => 'payment',
            'entity_type' => 'Pesanan',
            'entity_id'   => $pesanan->id,
            'description' => "Konfirmasi COD: #{$pesanan->nomor}",
        ]);

        session()->flash('success', 'Pembayaran COD dikonfirmasi!');
    }
}