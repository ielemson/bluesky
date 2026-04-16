<?php

namespace App\Http\Controllers;

use App\Models\PayoutWalletOption;
use App\Models\UserPayoutWallet;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserPayoutWalletController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $wallets = UserPayoutWallet::with('walletOption')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $walletOptions = PayoutWalletOption::query()
            ->where('is_active', true)
            ->orderBy('currency')
            ->orderBy('chain')
            ->get();

        return view('customer.wallet.index', compact('wallets', 'walletOptions'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'payout_wallet_option_id' => ['required', 'exists:payout_wallet_options,id'],
            'wallet_address' => ['required', 'string', 'max:255'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        $data['wallet_address'] = trim($data['wallet_address']);
        $isDefault = $request->boolean('is_default');

        $option = PayoutWalletOption::query()
            ->where('id', $data['payout_wallet_option_id'])
            ->where('is_active', true)
            ->first();

        if (!$option) {
            return redirect()
                ->route('customer.wallets.index')
                ->withErrors([
                    'payout_wallet_option_id' => 'The selected wallet option is invalid or inactive.',
                ])
                ->withInput();
        }

        $existingWallet = UserPayoutWallet::query()
            ->where('user_id', $user->id)
            ->where('payout_wallet_option_id', $option->id)
            ->where('wallet_address', $data['wallet_address'])
            ->first();

        if ($existingWallet) {
            if ($isDefault && !$existingWallet->is_default) {
                DB::transaction(function () use ($user, $existingWallet) {
                    UserPayoutWallet::where('user_id', $user->id)->update(['is_default' => false]);
                    $existingWallet->update(['is_default' => true]);
                });

                return redirect()
                    ->route('customer.wallets.index')
                    ->with('status', 'Wallet already existed and has been set as default.');
            }

            return redirect()
                ->route('customer.wallets.index')
                ->withErrors([
                    'wallet_address' => 'This wallet address has already been added for the selected currency and chain.',
                ])
                ->withInput();
        }

        try {
            DB::transaction(function () use ($user, $option, $data, $isDefault) {
                if ($isDefault) {
                    UserPayoutWallet::where('user_id', $user->id)->update(['is_default' => false]);
                }

                UserPayoutWallet::create([
                    'user_id' => $user->id,
                    'payout_wallet_option_id' => $option->id,
                    'wallet_address' => $data['wallet_address'],
                    'is_default' => $isDefault,
                ]);
            });
        } catch (QueryException $e) {
            if ((int) $e->getCode() === 23000) {
                return redirect()
                    ->route('customer.wallets.index')
                    ->withErrors([
                        'wallet_address' => 'This wallet address has already been added for the selected currency and chain.',
                    ])
                    ->withInput();
            }

            throw $e;
        }

        return redirect()
            ->route('customer.wallets.index')
            ->with('status', 'Wallet payout option added successfully.');
    }

    public function destroy(UserPayoutWallet $wallet)
    {
        abort_if($wallet->user_id !== Auth::id(), 403);

        $wallet->delete();

        return redirect()
            ->route('customer.wallets.index')
            ->with('status', 'Wallet removed successfully.');
    }

    public function setDefault(UserPayoutWallet $wallet)
    {
        abort_if($wallet->user_id !== Auth::id(), 403);

        DB::transaction(function () use ($wallet) {
            UserPayoutWallet::where('user_id', Auth::id())->update(['is_default' => false]);
            $wallet->update(['is_default' => true]);
        });

        return redirect()
            ->route('customer.wallets.index')
            ->with('status', 'Default wallet updated successfully.');
    }
}