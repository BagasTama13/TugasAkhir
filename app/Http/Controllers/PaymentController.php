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

class PaymentController extends Controller
{
    private function setupMidtrans(): void
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
        Config::$curlOptions = [
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER    => [],
        ];
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

        // If there's already a valid snap_token, reuse it
        if ($pesanan->snap_token) {
            return response()->json(['snap_token' => $pesanan->snap_token]);
        }

        $this->setupMidtrans();

        $totalHarga = (int) ($pesanan->total_harga ?? ($pesanan->jumlah * ($pesanan->produk->harga ?? 0)));

        $params = [
            'transaction_details' => [
                'order_id'    => $pesanan->nomor . '-' . time(),
                'gross_amount' => $totalHarga,
            ],
            'customer_details' => [
                'first_name' => $pesanan->nama,
                'phone'      => $pesanan->no_whatsapp,
            ],
            'item_details' => [
                [
                    'id'       => $pesanan->produk_id ?? 'PRODUK',
                    'price'    => $totalHarga,
                    'quantity' => 1,
                    'name'     => ($pesanan->produk->nama ?? 'Pesanan') . ' - ' . $pesanan->nomor,
                ],
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            $pesanan->update(['snap_token' => $snapToken]);
            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            Log::error('Midtrans getSnapToken error: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal menghubungi Midtrans: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Handle Midtrans webhook notification.
     * This route must be CSRF-exempt.
     */
    public function handleNotification(Request $request)
    {
        $this->setupMidtrans();

        try {
            $notification = new Notification();

            $transactionStatus = $notification->transaction_status;
            $orderId           = $notification->order_id; // e.g. "USR-XXXX-timestamp"
            $fraudStatus       = $notification->fraud_status;
            $transactionId     = $notification->transaction_id;

            // Extract original nomor from order_id (strip the -timestamp suffix)
            $nomor = preg_replace('/-\d+$/', '', $orderId);
            $pesanan = Pesanan::where('nomor', $nomor)->first();

            if (!$pesanan) {
                Log::warning('Midtrans notification: pesanan not found for order_id=' . $orderId);
                return response()->json(['message' => 'Pesanan not found'], 404);
            }

            Log::info("Midtrans notification: order={$orderId}, status={$transactionStatus}, fraud={$fraudStatus}");

            if ($transactionStatus === 'capture') {
                if ($fraudStatus === 'accept') {
                    $this->markAsPaid($pesanan, $transactionId);
                }
            } elseif ($transactionStatus === 'settlement') {
                $this->markAsPaid($pesanan, $transactionId);
            } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                // Clear snap token so user can retry
                $pesanan->update(['snap_token' => null]);
            }

            return response()->json(['message' => 'OK']);
        } catch (\Exception $e) {
            Log::error('Midtrans notification error: ' . $e->getMessage());
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

    /**
     * Test endpoint – kept for development.
     */
    public function test()
    {
        $this->setupMidtrans();

        $params = [
            'transaction_details' => [
                'order_id'    => 'TEST-' . time(),
                'gross_amount' => 10000,
            ],
            'customer_details' => [
                'first_name' => 'Bagas',
                'phone'      => '08123456789',
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return view('payment.test', compact('snapToken'));
    }
}