<?php

namespace App\Services;

use App\Models\Pesanan;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Hanya matikan SSL verification di local development
        if (app()->environment('local')) {
            Config::$curlOptions = [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_HTTPHEADER    => [],
            ];
        }
    }

    /**
     * Generate Snap Token for a given pesanan.
     */
    public function generateSnapToken(Pesanan $pesanan): ?string
    {
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
            return Snap::getSnapToken($params);
        } catch (\Exception $e) {
            Log::error('Midtrans generateSnapToken error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Parse Midtrans notification and verify payload signature.
     */
    public function parseNotification(): array
    {
        $notification = new \Midtrans\Notification();
        $serverKey = config('midtrans.server_key');

        $orderId = $notification->order_id ?? '';
        $statusCode = $notification->status_code ?? '';
        $grossAmount = $notification->gross_amount ?? '';
        $signatureKey = $notification->signature_key ?? '';

        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
        $isValidSignature = hash_equals($expectedSignature, $signatureKey);

        return [
            'is_valid_signature' => $isValidSignature,
            'transaction_status' => $notification->transaction_status,
            'order_id'           => $orderId,
            'fraud_status'       => $notification->fraud_status,
            'transaction_id'     => $notification->transaction_id,
            'signature_key'      => $signatureKey,
        ];
    }
}
