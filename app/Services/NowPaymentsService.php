<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class NowPaymentsService
{
    protected $base = 'https://api.nowpayments.io/v1';
    protected $apiKey;
    protected $callback;

    public function __construct()
    {
        $this->apiKey   = env('NOWPAYMENTS_API_KEY');
        $this->callback = env('NOWPAYMENTS_CALLBACK_URL');
    }

    public function createInvoice($amount, $orderId, $payCurrency = 'usdttrc20', $priceCurrency = 'usd')
    {
        return Http::withHeaders([
            'x-api-key' => $this->apiKey,
        ])->post($this->base.'/invoice', [
            'price_amount'     => $amount,
            'price_currency'   => $priceCurrency,
            'pay_currency'     => $payCurrency,
            'order_id'         => $orderId,
            'ipn_callback_url' => $this->callback,
        ])->json();
    }
}
