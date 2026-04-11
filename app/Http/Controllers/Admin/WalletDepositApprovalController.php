<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WalletDeposit;
use App\Models\UserWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;


class WalletDepositApprovalController extends Controller
{
    public function index()
    {
        $deposits = WalletDeposit::with(['user', 'paymentWallet'])
            ->latest()
            ->paginate(20);

        return view('admin.wallets.wallet_deposits', compact('deposits'));
    }

    // public function showJson(WalletDeposit $deposit)
    // {
    //     $deposit->load(['user', 'paymentWallet', 'reviewer']);

    //     return view('admin.wallet_deposits.show', compact('deposit'));
    // }

    public function showJson(WalletDeposit $deposit): JsonResponse
{
    $deposit->load(['user', 'paymentWallet', 'reviewer']);

    return response()->json([
        'id'        => $deposit->id,
        'user'      => [
            'id'    => $deposit->user_id,
            'name'  => optional($deposit->user)->name,
            'email' => optional($deposit->user)->email,
        ],
        'method'    => optional($deposit->paymentWallet)->method,
        'network'   => optional($deposit->paymentWallet)->network,
        'amount'    => $deposit->amount,
        'currency'  => $deposit->currency,
        'status'    => $deposit->status,
        'created_at'=> $deposit->created_at->format('Y-m-d H:i:s'),
        'admin_note'=> $deposit->admin_note,
        'proof_url' => $deposit->proof_path
            ? asset('storage/'.$deposit->proof_path)
            : null,
    ]);
}

    public function approveAjax(WalletDeposit $deposit, Request $request): \Illuminate\Http\JsonResponse
    {
        if ($deposit->status !== 'pending') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Deposit already processed.',
            ], 422);
        }

        DB::transaction(function () use ($deposit, $request) {
            $wallet = UserWallet::firstOrCreate(
                ['user_id' => $deposit->user_id],
                ['account_balance' => 0, 'available_balance' => 0, 'on_hold' => 0]
            );

            $wallet->increment('account_balance', $deposit->amount);
            $wallet->increment('available_balance', $deposit->amount);

            $deposit->update([
                'status'      => 'approved',
                'admin_note'  => $request->input('admin_note'),
                'reviewed_at' => now(),
                'reviewed_by' => Auth::id(),
            ]);
        });

        return response()->json([
            'status'  => 'success',
            'message' => 'Deposit approved and wallet funded.',
        ]);
    }

    public function rejectAjax(WalletDeposit $deposit, Request $request): \Illuminate\Http\JsonResponse
    {
        if ($deposit->status !== 'pending') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Deposit already processed.',
            ], 422);
        }

        $request->validate([
            'admin_note' => 'nullable|string|max:1000',
        ]);

        $deposit->update([
            'status'      => 'rejected',
            'admin_note'  => $request->input('admin_note'),
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id(),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Deposit rejected.',
        ]);
    }
}
