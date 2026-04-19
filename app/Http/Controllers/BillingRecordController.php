<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;

class BillingRecordController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $billingRecords = WalletTransaction::query()
            ->where('user_id', $user->id)
            ->latest('posted_at')
            ->latest('id')
            ->paginate(15);

        return view('customer.billing.index', compact('billingRecords'));
    }

    public function show(Request $request, WalletTransaction $billingRecord)
    {
        abort_unless((int) $billingRecord->user_id === (int) $request->user()->id, 403);

        return view('customer.billing.show', compact('billingRecord'));
    }
}
