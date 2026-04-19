<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserWallet;
use App\Models\WalletDeposit;
use App\Models\WalletTransaction;
use App\Services\UserMessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WalletDepositApprovalController extends Controller
{
    public function index()
    {
        $deposits = WalletDeposit::with(['user', 'paymentWallet'])
            ->latest()
            ->paginate(20);

        return view('admin.wallets.wallet_deposits', compact('deposits'));
    }

    public function showJson(WalletDeposit $deposit): JsonResponse
    {
        $deposit->load(['user', 'paymentWallet', 'reviewer']);

        return response()->json([
            'id'          => $deposit->id,
            'user'        => [
                'id'    => $deposit->user_id,
                'name'  => optional($deposit->user)->name,
                'email' => optional($deposit->user)->email,
            ],
            'method'      => optional($deposit->paymentWallet)->method,
            'network'     => optional($deposit->paymentWallet)->network,
            'amount'      => $deposit->amount,
            'currency'    => $deposit->currency,
            'status'      => $deposit->status,
            'created_at'  => optional($deposit->created_at)?->format('Y-m-d H:i:s'),
            'admin_note'  => $deposit->admin_note,
            'proof_url'   => $deposit->proof_path
                ? asset('storage/' . $deposit->proof_path)
                : null,
        ]);
    }

    public function approveAjax(WalletDeposit $deposit, Request $request): JsonResponse
    {
        $request->validate([
            'admin_note' => 'nullable|string|max:1000',
        ]);

        $result = DB::transaction(function () use ($deposit, $request) {
            $approvedDeposit = WalletDeposit::with(['paymentWallet', 'user'])
                ->whereKey($deposit->id)
                ->lockForUpdate()
                ->first();

            if (!$approvedDeposit || $approvedDeposit->status !== 'pending') {
                abort(422, 'Deposit already processed.');
            }

            $wallet = UserWallet::where('user_id', $approvedDeposit->user_id)
                ->lockForUpdate()
                ->first();

            if (!$wallet) {
                $wallet = UserWallet::create([
                    'user_id' => $approvedDeposit->user_id,
                    'account_balance' => 0,
                    'available_balance' => 0,
                    'on_hold' => 0,
                ]);

                $wallet = UserWallet::whereKey($wallet->id)
                    ->lockForUpdate()
                    ->first();
            }

            $balanceBefore = (float) $wallet->available_balance;
            $balanceAfter  = $balanceBefore + (float) $approvedDeposit->amount;

            $wallet->available_balance = $balanceAfter;
            $wallet->account_balance   = $balanceAfter + (float) $wallet->on_hold;
            $wallet->save();

            $approvedDeposit->update([
                'status'      => 'approved',
                'admin_note'  => $request->input('admin_note'),
                'reviewed_at' => now(),
                'reviewed_by' => Auth::id(),
            ]);

            $reference = $approvedDeposit->transaction_reference
                ? trim($approvedDeposit->transaction_reference)
                : 'DEP-' . strtoupper(Str::random(10));

            WalletTransaction::create([
                'user_id'         => $approvedDeposit->user_id,
                'direction'       => 'credit',
                'category'        => 'deposit',
                'amount'          => $approvedDeposit->amount,
                'balance_before'  => $balanceBefore,
                'balance_after'   => $balanceAfter,
                'reference'       => $reference,
                'source_type'     => WalletDeposit::class,
                'source_id'       => $approvedDeposit->id,
                'status'          => 'completed',
                'description'     => 'Approved wallet deposit',
                'posted_at'       => now(),
                'meta'            => [
                    'payment_wallet_id' => $approvedDeposit->payment_wallet_id,
                    'currency'          => $approvedDeposit->currency,
                    'method'            => optional($approvedDeposit->paymentWallet)->method,
                    'network'           => optional($approvedDeposit->paymentWallet)->network,
                    'admin_note'        => $request->input('admin_note'),
                    'reviewed_by'       => Auth::id(),
                ],
            ]);

            return [
                'approvedDeposit' => $approvedDeposit->fresh(['paymentWallet']),
                'wallet' => $wallet->fresh(),
                'reference' => $reference,
            ];
        });

        $approvedDeposit = $result['approvedDeposit'];
        $wallet = $result['wallet'];
        $reference = $result['reference'];

        UserMessageService::send(
            userId: $approvedDeposit->user_id,
            title: 'Deposit Approved',
            message: 'Your deposit of $' . number_format((float) $approvedDeposit->amount, 2) . ' has been approved and credited to your wallet.',
            type: 'wallet',
            meta: [
                'action' => 'deposit_approved',
                'amount' => (float) $approvedDeposit->amount,
                'currency' => $approvedDeposit->currency,
                'reference' => $reference,
                'new_balance' => (float) $wallet->available_balance,
                'payment_wallet_id' => $approvedDeposit->payment_wallet_id,
                'method' => optional($approvedDeposit->paymentWallet)->method,
                'network' => optional($approvedDeposit->paymentWallet)->network,
                'admin_note' => $approvedDeposit->admin_note,
                'deposit_id' => $approvedDeposit->id,
            ]
        );

        return response()->json([
            'status'  => 'success',
            'message' => 'Deposit approved and wallet funded.',
        ]);
    }

    public function rejectAjax(WalletDeposit $deposit, Request $request): JsonResponse
    {
        $request->validate([
            'admin_note' => 'nullable|string|max:1000',
        ]);

        $rejectedDeposit = DB::transaction(function () use ($deposit, $request) {
            $deposit = WalletDeposit::whereKey($deposit->id)
                ->lockForUpdate()
                ->first();

            if (!$deposit || $deposit->status !== 'pending') {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Deposit already processed.',
                ], 422)->throwResponse();
            }

            $deposit->update([
                'status'      => 'rejected',
                'admin_note'  => $request->input('admin_note'),
                'reviewed_at' => now(),
                'reviewed_by' => Auth::id(),
            ]);

            return $deposit->fresh();
        });

        UserMessageService::send(
            userId: $rejectedDeposit->user_id,
            title: 'Deposit Rejected',
            message: 'Your deposit request was rejected. Please review the note from support/admin.',
            type: 'wallet',
            meta: [
                'action' => 'deposit_rejected',
                'amount' => (float) $rejectedDeposit->amount,
                'currency' => $rejectedDeposit->currency,
                'deposit_id' => $rejectedDeposit->id,
                'admin_note' => $rejectedDeposit->admin_note,
            ]
        );

        return response()->json([
            'status'  => 'success',
            'message' => 'Deposit rejected.',
        ]);
    }
}