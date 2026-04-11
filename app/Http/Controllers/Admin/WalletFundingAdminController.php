<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WalletFundingRequest;

class WalletFundingAdminController extends Controller
{
    // List all requests (you can filter by status from view)
    public function index()
    {
        $requests = WalletFundingRequest::with('user', 'paymentWallet')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.wallet_funding.index', compact('requests'));
    }

    // Show single request with payment evidence
    public function show($id)
    {
        $requestFunding = WalletFundingRequest::with('user', 'paymentWallet')
            ->findOrFail($id);

        return view('admin.wallet_funding.show', compact('requestFunding'));
    }

    // Approve and fund wallet
    public function approve($id)
    {
        $requestFunding = WalletFundingRequest::where('status', 'pending')
            ->findOrFail($id);

        DB::transaction(function () use ($requestFunding) {
            // update request status
            $requestFunding->update([
                'status'      => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            // credit user wallet
            $wallet = UserWallet::firstOrCreate(
                ['user_id' => $requestFunding->user_id],
                ['balance' => 0]
            );

            $wallet->balance = $wallet->balance + $requestFunding->amount;
            $wallet->save();
        });

        return redirect()
            ->route('admin.wallet-funding.index')
            ->with('success', 'Request approved and wallet funded.');
    }

    // Reject without funding
    public function reject($id)
    {
        $requestFunding = WalletFundingRequest::where('status', 'pending')
            ->findOrFail($id);

        $requestFunding->update([
            'status'      => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()
            ->route('admin.wallet-funding.index')
            ->with('success', 'Request rejected.');
    }
}