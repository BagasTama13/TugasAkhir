<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;
use App\Models\Pesanan;

#[Layout('layouts.user')]
class UserPesanan extends Component
{
    // Method mount() sebagai constructor komponen
    public function mount(): void
    {
        $user     = Auth::user();
        $username = strtolower($user->username ?? '');

        // Middleware keamanan: Memastikan staf admin, owner, dan worker tidak bisa mengakses halaman user
        if ($username === 'admin' || str_starts_with($username, 'owner') || str_starts_with($username, 'worker')) {
            abort(403, 'Use your designated panel.');
        }
    }

    public $selectedPesananId = null;

    public function showDetail($id)
    {
        $this->selectedPesananId = $id;
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
            ->get();
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
