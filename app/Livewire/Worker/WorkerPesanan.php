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

// Class WorkerPesanan mewarisi fungsionalitas dari Admin\Pesanan (OOP Inheritance).
// Pekerja (Worker/Driver) menggunakan sistem yang sama dengan Admin, namun ada limitasi fungsional.
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
     * Override Data Query:
     * Filter data secara dinamis. Worker TIDAK BOLEH melihat pesanan 'pending' atau 'rejected'.
     * Worker hanya memproses pesanan yang sudah mendapat lampu hijau dari Admin,
     * yaitu pesanan bersatus: 'dalam_antrian' (siap angkut), 'diproses' (sedang di jalan), atau 'terkirim' (sudah selesai).
     */
    #[Computed]
    public function pesanans()
    {
        return PesananModel::with(['user', 'produk'])
            ->whereIn('status', ['dalam_antrian', 'diproses', 'terkirim'])
            ->latest()
            ->paginate(15);
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
     * Logika Aksi Operasional 1 (Mulai Pengiriman):
     * Worker mengklik "Proses" ketika barang mulai dinaikkan ke truk atau siap meluncur ke lokasi pembeli.
     * Status berubah: dalam_antrian → diproses
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
     * Logika Aksi Operasional 2 (Selesai Pengiriman):
     * Worker mengklik "Konfirmasi Kirim" ketika barang fisik sudah mendarat di lokasi pelanggan.
     * Status berubah: diproses → terkirim
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


}