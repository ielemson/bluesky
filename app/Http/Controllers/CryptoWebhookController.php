<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CryptoWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $secret    = env('NOWPAYMENTS_IPN_SECRET');
        $signature = $request->header('x-nowpayments-sig');
        $payload   = $request->getContent();

        // Hash & verify
        $calc = hash_hmac('sha512', $payload, $secret);

        if (!hash_equals($calc, $signature)) {
            Log::error("NOWPayments: invalid signature", [
                'expected' => $calc,
                'got'      => $signature,
                'payload'  => $payload,
            ]);
            return response()->json(['error' => 'invalid signature'], 400);
        }

        Log::info("NOWPayments webhook OK", [
            'data' => $request->all()
        ]);

        return response()->json(['status' => 'received']);
    }
}
