<?php

namespace App\Http\Controllers;

use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function test()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;

        Config::$curlOptions = [
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => [],
        ];

        $params = [
            'transaction_details' => [
                'order_id' => 'TEST-' . time(),
                'gross_amount' => 10000,
            ],
            'customer_details' => [
                'first_name' => 'Bagas',
                'phone' => '08123456789',
            ]
        ];

        $snapToken = Snap::getSnapToken($params);

        return view('payment.test', compact('snapToken'));
    }
}