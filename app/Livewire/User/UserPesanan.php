<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;
use App\Models\Pesanan;

use Livewire\WithPagination;

#[Layout('layouts.user')]
class UserPesanan extends Component
{
    use WithPagination;

    public $selectedPesananId = null;

    public function showDetail($id)
    {
        $this->selectedPesananId = $id;

        // Pre-fetch snap token di background saat modal dibuka
        // sehingga token sudah siap ketika user klik "Bayar Sekarang"
        $pesanan = Pesanan::find($id);
        if ($pesanan
            && in_array($pesanan->status, ['dalam_antrian', 'diproses', 'terkirim'])
            && $pesanan->payment_status === 'belum_dibayar'
        ) {
            $this->dispatch('prefetch-snap-token', [
                'pesananId' => $id,
                'csrfToken' => csrf_token(),
            ]);
        }
    }

    public function closeDetail()
    {
        $this->selectedPesananId = null;
    }

    #[Computed]
    public function selectedPesanan()
    {
        if ($this->selectedPesananId) {
            return Pesanan::with('produk')->find($this->selectedPesananId);
        }
        return null;
    }

    #[Computed]
    public function pesanans()
    {
        return Pesanan::where('user_id', Auth::id())
            ->with('produk')
            ->latest()
            ->paginate(15);
    }

    /**
     * Listener Livewire (Webhook Call):
     * Dipanggil secara asinkron saat Midtrans mengirimkan sinyal pembayaran berhasil.
     * Ini me-refresh status pesanan pada komponen agar status "Lunas" langsung ter-update di layar pelanggan.
     */
    public function handlePaymentSuccess($pesananId): void
    {
        // Re-query so updated payment_status is shown
        $this->selectedPesananId = $pesananId;
        unset($this->pesanans);
        unset($this->selectedPesanan);
    }

    /**
     * Logika Pembayaran Alternatif (Client-Side Callback):
     * Terkadang webhook API (Server-to-Server) gagal menjangkau server lokal.
     * Fungsi ini bertindak sebagai skenario fallback yang dipicu lewat JavaScript (window.snap.pay -> onSuccess),
     * memastikan pesanan tetap terverifikasi dan uang masuk dicatat.
     */
    public function confirmPaymentFromClient(int $pesananId, string $transactionId = '')
    {
        $pesanan = Pesanan::where('id', $pesananId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$pesanan) return;
        if ($pesanan->payment_status === 'telah_dibayar') {
            // Already paid, just refresh UI
            $this->handlePaymentSuccess($pesananId);
            return;
        }

        $pesanan->update([
            'payment_status'          => 'telah_dibayar',
            'midtrans_transaction_id' => $transactionId ?: $pesanan->midtrans_transaction_id,
            'paid_at'                 => now(),
        ]);

        // Membuat Laporan Keuangan (Pemasukan) karena uang digital telah masuk (Confirmed)
        \App\Models\Pemasukan::updateOrCreate(
            ['pesanan_id' => $pesanan->id],
            [
                'tanggal'    => today(),
                'jumlah'     => $pesanan->total_harga,
                'keterangan' => "Pembayaran Online (Midtrans): {$pesanan->nomor} ({$pesanan->nama})",
                'kategori'   => 'penjualan',
                'status'     => 'confirmed',
                'user_id'    => Auth::id(),
            ]
        );

        \App\Models\Activity::create([
            'user_id'     => Auth::id(),
            'action'      => 'payment',
            'entity_type' => 'Pesanan',
            'entity_id'   => $pesanan->id,
            'description' => "Pembayaran online berhasil: #{$pesanan->nomor}",
        ]);

        session()->flash('success', 'Pembayaran berhasil! Terima kasih.');
        return $this->redirectRoute('user.dashboard', navigate: true);
    }

    public function render()
    {
        return view('livewire.user.pesanan');
    }
}
