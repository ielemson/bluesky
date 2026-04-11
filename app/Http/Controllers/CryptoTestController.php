<?php

namespace App\Http\Controllers;

use App\Services\NowPaymentsService;
use Illuminate\Http\Request;

class CryptoTestController extends Controller
{
    public function startTestPayment()
    {
        $np = new NowPaymentsService();

        $fakeOrderId = "TEST-" . time();

        $invoice = $np->createInvoice(
            amount: 10,         // $10 test payment
            orderId: $fakeOrderId,
            payCurrency: 'usdttrc20'
        );

        if (!empty($invoice['invoice_url'])) {
            return redirect($invoice['invoice_url']);
        }

        return $invoice; // show raw response for debugging
    }
}
