<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PayoutWalletOption;
use App\Models\UserPayoutWallet;
use App\Models\WithdrawalRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class WithdrawalRequestController extends Controller
{
     public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'withdraw_method_id' => ['required', 'exists:payout_wallet_options,id'],
            'withdraw_type'      => ['required', 'in:bank,crypto'],
            'amount'             => ['required', 'numeric', 'min:0.01'],
            'password'           => ['required', 'string'],

            // crypto
            'address_id'         => ['nullable', 'integer'],

            // bank
            'bank_name'          => ['nullable', 'string', 'max:255'],
            'bank_code'          => ['nullable', 'string', 'max:50'],
            'bank_account_number'=> ['nullable', 'string', 'max:50'],
            'account_name'       => ['nullable', 'string', 'max:255'],
            'bank_branch'        => ['nullable', 'string', 'max:255'],
        ]);

        // If you store transaction password separately, replace this block.
        if (!Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'message' => 'Incorrect transaction password.'
            ], 422);
        }

        $option = PayoutWalletOption::where('id', $validated['withdraw_method_id'])
            ->where('is_active', 1)
            ->first();

        if (!$option) {
            return response()->json([
                'message' => 'Selected withdrawal method is not available.'
            ], 422);
        }

        $amount = (float) $validated['amount'];
        $fee = 0;
        $netAmount = $amount - $fee;

        // If you have wallet balance validation, use your real wallet source here.
        if (method_exists($user, 'wallet') && $user->wallet) {
            $availableBalance = (float) ($user->wallet->available_balance ?? 0);

            if ($amount > $availableBalance) {
                return response()->json([
                    'message' => 'Insufficient available balance.'
                ], 422);
            }
        }

        $payload = [
            'user_id'                 => $user->id,
            'payout_wallet_option_id' => $option->id,
            'method_type'             => $validated['withdraw_type'] === 'bank' ? 'online_banking' : 'crypto',
            'amount'                  => $amount,
            'fee'                     => $fee,
            'net_amount'              => $netAmount,
            'status'                  => 'pending',
            'request_currency'        => $validated['withdraw_type'] === 'bank'
                                            ? 'NGN'
                                            : ($option->currency ?? 'USDT'),
            'option_currency'         => $option->currency,
            'option_chain'            => $option->chain,
        ];

        if ($validated['withdraw_type'] === 'crypto') {
            $wallet = UserPayoutWallet::where('id', $validated['address_id'] ?? 0)
                ->where('user_id', $user->id)
                ->first();

            if (!$wallet) {
                return response()->json([
                    'message' => 'Please select a valid withdrawal address.'
                ], 422);
            }

            $payload = array_merge($payload, [
                'user_payout_wallet_id' => $wallet->id,
                'crypto_currency'       => $option->currency,
                'crypto_chain'          => $option->chain,
                'wallet_address'        => $wallet->wallet_address ?? $wallet->address ?? null,
                'wallet_tag_memo'       => $wallet->tag_memo ?? null,

                'bank_name'             => null,
                'bank_code'             => null,
                'account_name'          => null,
                'account_number'        => null,
                'bank_branch'           => null,
            ]);
        } else {
            if (
                empty($validated['bank_name']) ||
                empty($validated['bank_code']) ||
                empty($validated['bank_account_number']) ||
                empty($validated['account_name'])
            ) {
                return response()->json([
                    'message' => 'Please complete all required bank withdrawal fields.'
                ], 422);
            }

            $payload = array_merge($payload, [
                'bank_name'       => $validated['bank_name'],
                'bank_code'       => $validated['bank_code'],
                'account_name'    => $validated['account_name'],
                'account_number'  => $validated['bank_account_number'],
                'bank_branch'     => $validated['bank_branch'] ?? null,

                'crypto_currency' => null,
                'crypto_chain'    => null,
                'wallet_address'  => null,
                'wallet_tag_memo' => null,
            ]);
        }

        $withdrawal = WithdrawalRequest::create($payload);

        return response()->json([
            'status'  => true,
            'message' => 'Withdrawal request submitted successfully.',
            'data'    => [
                'id'     => $withdrawal->id,
                'amount' => $withdrawal->amount,
                'type'   => $withdrawal->method_type,
            ]
        ]);
    }
}
