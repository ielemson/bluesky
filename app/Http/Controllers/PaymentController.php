<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order; // Assuming your order model

class PaymentController extends Controller
{
    /**
     * Create a crypto payment invoice with NowPayments
     */
    public function createPayment(Request $request)
    {
        // Validate form inputs
        $request->validate([
            'contact_person' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'address' => 'required|string|max:500',
            'total_amount' => 'required|numeric|min:0.01',
            'crypto' => 'required|string'
        ]);

        // Ensure total is numeric
        $amount = (float) str_replace([',', '$'], '', $request->total_amount);

        $payload = [
            'price_amount' => $amount,
            'price_currency' => 'usd',
            'pay_currency' => $request->crypto,
            'order_id' => uniqid('order_'),
            'order_description' => "Payment for {$request->contact_person}",
            'ipn_callback_url' => route('payment.callback'),
            'success_url' => url('/order/success'),
            'cancel_url' => url('/order/cancel')
        ];

        try {
            $ch = curl_init("https://api.nowpayments.io/v1/invoice");
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "x-api-key: " . config('services.nowpayments.api_key'),
                "Content-Type: application/json"
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

            $response = curl_exec($ch);
            $err = curl_error($ch);
            curl_close($ch);

            if ($err) {
                \Log::error("NowPayments CURL error: " . $err);
                return response()->json([
                    'status' => false,
                    'message' => 'NowPayments request failed: ' . $err
                ]);
            }

            $res = json_decode($response, true);

            if (isset($res['invoice_url'])) {
                // Optionally: create order in your database here with status 'pending'
                // $order = Order::create([...]);

                return response()->json([
                    'status' => true,
                    'payment_url' => $res['invoice_url']
                ]);
            }

            // Log full response if no invoice_url
            \Log::error('NowPayments failed response', ['response' => $res]);

            return response()->json([
                'status' => false,
                'message' => 'NOWPayments request failed',
                'error' => $res
            ]);
        } catch (\Exception $e) {
            \Log::error('NowPayments exception: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Unexpected error occurred while creating payment.',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle NowPayments IPN callback
     */
    public function paymentCallback(Request $request)
    {
        \Log::info('NowPayments IPN', $request->all());

        $orderId = $request->order_id ?? null;
        $paymentStatus = $request->payment_status ?? null;

        if ($orderId && $paymentStatus) {
            $order = Order::where('order_number', $orderId)->first();

            if ($order) {
                if ($paymentStatus === 'finished') {
                    $order->payment_status = 'paid';
                    $order->order_status = 'processing';
                    $order->save();
                } elseif ($paymentStatus === 'failed') {
                    $order->payment_status = 'failed';
                    $order->order_status = 'cancelled';
                    $order->save();
                }
            }
        }

        // Must return 200 OK for NowPayments
        return response()->json(['status' => 'ok']);
    }
}
