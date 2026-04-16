<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserWallet;
use App\Services\UserMessageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
            'action' => ['required', 'in:credit,debit'], // 🔥 NEW
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

        DB::transaction(function () use ($data, $amount, $action, &$wallet, &$user) {

            $user = User::findOrFail($data['user_id']);

            $wallet = UserWallet::query()
                ->lockForUpdate()
                ->firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'account_balance' => 0,
                        'available_balance' => 0,
                        'on_hold' => 0,
                    ]
                );

            if ($action === 'credit') {
                $wallet->account_balance += $amount;
                $wallet->available_balance += $amount;
            }

            if ($action === 'debit') {
                // 🚨 Prevent overdraft
                if ($wallet->available_balance < $amount) {
                    abort(422, 'Insufficient wallet balance for this deduction.');
                }

                $wallet->account_balance -= $amount;
                $wallet->available_balance -= $amount;
            }

            $wallet->save();
        });

        // 🔔 SEND USER MESSAGE
        UserMessageService::send(
            userId: $user->id,
            title: $action === 'credit' ? 'Wallet Funded' : 'Wallet Debited',
            message: $action === 'credit'
                ? 'Your wallet has been credited with $' . number_format($amount, 2)
                : '$' . number_format($amount, 2) . ' has been deducted from your wallet.',
            type: 'wallet',
            meta: [
                'action' => $action,
                'amount' => $amount,
                'reference' => $reference,
                'new_balance' => (float) $wallet->available_balance,
                'admin_note' => $data['admin_note'] ?? 'Admin wallet adjustment',
                'proof_path' => $proofPath,
            ]
        );

        return redirect()
            ->route('admin.wallet-options.topup')
            ->with('success', 'Wallet ' . ($action === 'credit' ? 'funded' : 'debited') . ' successfully.');
    }

}