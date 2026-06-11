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
    public function mount(): void
    {
        $user     = Auth::user();
        $username = strtolower($user->username ?? '');

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
     * Called by Livewire after the user successfully paid via Midtrans Snap.
     * Refreshes the component so payment_status is updated.
     */
    public function handlePaymentSuccess($pesananId): void
    {
        // Re-query so updated payment_status is shown
        $this->selectedPesananId = $pesananId;
        unset($this->pesanans);
        unset($this->selectedPesanan);
    }

    /**
     * Called directly from JS onSuccess callback to mark payment as paid.
     * This is the fallback for when webhook cannot reach localhost.
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

        // Update or create Pemasukan as confirmed
        \App\Models\Pemasukan::updateOrCreate(
            ['pesanan_id' => $pesanan->id],
            [
                'tanggal'    => today(),
                'jumlah'     => $pesanan->total_harga,
                'keterangan' => "Pembayaran Online: {$pesanan->nomor} ({$pesanan->nama})",
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
