<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Pemasukan;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Notification;
use Midtrans\Snap;

use App\Services\MidtransService;

class PaymentController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Generate Snap Token for a given pesanan and return as JSON.
     * Called via AJAX from the user pesanan page.
     */
    public function getSnapToken(Request $request, int $id)
    {
        $pesanan = Pesanan::with(['user', 'produk'])->findOrFail($id);

        // Only the owner of this pesanan can pay it
        if ($pesanan->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Already paid
        if ($pesanan->payment_status === 'telah_dibayar') {
            return response()->json(['error' => 'Pesanan sudah dibayar.'], 422);
        }

        // Reuse stored snap_token for instant response (DB lookup vs API call).
        // Expired/cancelled tokens are cleared by the Midtrans webhook handler below.
        // If a user reports a failed popup, they can simply refresh the page to get a fresh token.
        if ($pesanan->snap_token) {
            return response()->json(['snap_token' => $pesanan->snap_token]);
        }

        // No stored token yet — call Midtrans API to generate one
        $snapToken = $this->midtransService->generateSnapToken($pesanan);

        if ($snapToken) {
            $pesanan->update(['snap_token' => $snapToken]);
            return response()->json(['snap_token' => $snapToken]);
        }

        return response()->json(['error' => 'Gagal menghubungi Midtrans. Coba lagi.'], 500);
    }

    /**
     * Menangani notifikasi webhook dari Midtrans.
     * Route ini dikecualikan dari perlindungan CSRF (Token) agar sistem 
     * dapat menerima HTTP POST dari server eksternal Midtrans.
     */
    public function handleNotification(Request $request)
    {
        try {
            // Mem-parsing muatan data (payload) notifikasi dari API Midtrans
            $data = $this->midtransService->parseNotification();

            // Pengecekan Keamanan: Memvalidasi Midtrans Signature Key 
            // untuk mencegah intrusi atau pemalsuan webhook (webhook forgery)
            if (empty($data['is_valid_signature'])) {
                Log::warning('Notifikasi Midtrans: Signature key tidak valid untuk order_id=' . ($data['order_id'] ?? 'unknown'));
                return response()->json(['message' => 'Invalid signature key'], 403);
            }

            $transactionStatus = $data['transaction_status'];
            $orderId           = $data['order_id']; // contoh format: "USR-XXXX-timestamp"
            $fraudStatus       = $data['fraud_status'];
            $transactionId     = $data['transaction_id'];

            // Mengekstrak format nomor resi asli pesanan dari variabel order_id
            $nomor = preg_replace('/-\d+$/', '', $orderId);
            $pesanan = Pesanan::where('nomor', $nomor)->first();

            if (!$pesanan) {
                Log::warning('Notifikasi Midtrans: Pesanan tidak ditemukan untuk order_id=' . $orderId);
                return response()->json(['message' => 'Pesanan not found'], 404);
            }

            Log::info("Notifikasi Midtrans: order={$orderId}, status={$transactionStatus}, fraud={$fraudStatus}");

            // Memperbarui status pesanan menjadi Lunas secara dinamis jika pembayaran berhasil (capture/settlement)
            if ($transactionStatus === 'capture') {
                if ($fraudStatus === 'accept') {
                    $this->markAsPaid($pesanan, $transactionId);
                }
            } elseif ($transactionStatus === 'settlement') {
                $this->markAsPaid($pesanan, $transactionId);
            } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                // Menghapus token (snap) agar pelanggan dapat mengulang proses pembayaran
                $pesanan->update(['snap_token' => null]);
            }

            return response()->json(['message' => 'OK']);
        } catch (\Exception $e) {
            // Pencatatan (Logging) jika terjadi kegagalan skrip atau koneksi
            Log::error('Kesalahan notifikasi Midtrans: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Mark a pesanan as paid and update/create Pemasukan record.
     */
    private function markAsPaid(Pesanan $pesanan, string $transactionId): void
    {
        if ($pesanan->payment_status === 'telah_dibayar') {
            return; // Already paid, idempotent
        }

        $pesanan->update([
            'payment_status'          => 'telah_dibayar',
            'midtrans_transaction_id' => $transactionId,
            'paid_at'                 => now(),
        ]);

        // Update or create Pemasukan record as confirmed
        Pemasukan::updateOrCreate(
            ['pesanan_id' => $pesanan->id],
            [
                'tanggal'    => today(),
                'jumlah'     => $pesanan->total_harga,
                'keterangan' => "Pembayaran Online: {$pesanan->nomor} ({$pesanan->nama})",
                'kategori'   => 'penjualan',
                'status'     => 'confirmed',
                'user_id'    => $pesanan->user_id,
            ]
        );

        // Log activity
        Activity::create([
            'user_id'     => $pesanan->user_id,
            'action'      => 'payment',
            'entity_type' => 'Pesanan',
            'entity_id'   => $pesanan->id,
            'description' => "Pembayaran online berhasil: #{$pesanan->nomor}",
        ]);
    }

}