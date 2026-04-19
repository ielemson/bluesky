<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserWallet;
use App\Models\WalletTransaction;
use App\Services\UserMessageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdminWalletTopupController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()
            ->with(['wallet'])
            ->latest();

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('contact', 'like', "%{$search}%")
                    ->orWhere('customer_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('verified')) {
            if ($request->verified === 'verified') {
                $query->whereNotNull('email_verified_at');
            } elseif ($request->verified === 'unverified') {
                $query->whereNull('email_verified_at');
            }
        }

        $users = $query->paginate(20)->withQueryString();

        return view('admin.wallets.topup', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'action' => ['required', 'in:credit,debit'],
            'admin_note' => ['nullable', 'string'],
            'transaction_reference' => ['nullable', 'string', 'max:191'],
            'proof_path' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,webp', 'max:4096'],
        ]);

        $proofPath = null;

        if ($request->hasFile('proof_path')) {
            $proofPath = $request->file('proof_path')->store('admin-wallet-topups', 'public');
        }

        $amount = (float) $data['amount'];
        $action = $data['action'];

        $reference = !empty($data['transaction_reference'])
            ? trim($data['transaction_reference'])
            : strtoupper($action) . '-' . strtoupper(Str::random(10));

        $user = null;
        $wallet = null;
        $balanceAfter = 0;

        DB::transaction(function () use ($data, $amount, $action, $reference, $proofPath, &$wallet, &$user, &$balanceAfter) {
            $user = User::findOrFail($data['user_id']);

            $wallet = UserWallet::query()
                ->where('user_id', $user->id)
                ->lockForUpdate()
                ->first();

            if (!$wallet) {
                $wallet = UserWallet::create([
                    'user_id' => $user->id,
                    'account_balance' => 0,
                    'available_balance' => 0,
                    'on_hold' => 0,
                ]);

                $wallet = UserWallet::query()
                    ->where('id', $wallet->id)
                    ->lockForUpdate()
                    ->first();
            }

            $balanceBefore = (float) $wallet->available_balance;

            if ($action === 'credit') {
                $balanceAfter = $balanceBefore + $amount;
            } else {
                if ($balanceBefore < $amount) {
                    throw ValidationException::withMessages([
                        'amount' => 'Insufficient wallet balance for this deduction.',
                    ]);
                }

                $balanceAfter = $balanceBefore - $amount;
            }

            $wallet->available_balance = $balanceAfter;
            $wallet->account_balance = $balanceAfter + (float) $wallet->on_hold;
            $wallet->save();

            WalletTransaction::create([
                'user_id' => $user->id,
                'direction' => $action,
                'category' => $action === 'credit' ? 'admin_topup' : 'admin_debit',
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'reference' => $reference,
                'source_type' => UserWallet::class,
                'source_id' => $wallet->id,
                'status' => 'completed',
                'description' => $data['admin_note'] ?? 'Admin wallet adjustment',
                'posted_at' => now(),
                'meta' => [
                    'action' => $action,
                    'admin_note' => $data['admin_note'] ?? 'Admin wallet adjustment',
                    'proof_path' => $proofPath,
                    'performed_by' => auth()->id(),
                ],
            ]);
        });

        $title = $action === 'credit' ? 'Wallet Funded' : 'Wallet Debited';

        $message = $action === 'credit'
            ? 'Your wallet has been credited with $' . number_format($amount, 2) . '.'
            : '$' . number_format($amount, 2) . ' has been deducted from your wallet.';

        try {
            UserMessageService::send(
                $user->id,
                $title,
                $message,
                'wallet',
                [
                    'action' => $action,
                    'amount' => $amount,
                    'reference' => $reference,
                    'new_balance' => (float) $wallet->available_balance,
                    'admin_note' => $data['admin_note'] ?? 'Admin wallet adjustment',
                    'proof_path' => $proofPath,
                ]
            );
        } catch (\Throwable $e) {
            Log::error('User wallet message failed to send', [
                'user_id' => $user?->id,
                'reference' => $reference,
                'error' => $e->getMessage(),
            ]);
        }

        return redirect()
            ->route('admin.wallet-options.topup')
            ->with('success', 'Wallet ' . ($action === 'credit' ? 'funded' : 'debited') . ' successfully.');
    }
}