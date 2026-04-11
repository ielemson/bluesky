<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\WalletDeposit;
use App\Models\PaymentWallet;
use App\Models\UserWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class WalletDepositController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $wallet = UserWallet::firstOrCreate(
            ['user_id' => $userId],
            ['account_balance' => 0, 'available_balance' => 0, 'on_hold' => 0]
        );

        $deposits = WalletDeposit::where('user_id', $userId)
            ->with('paymentWallet')
            ->latest()
            ->get();

        // In the Blade view show:
        // account_balance, available_balance, on_hold, and deposit list
        return view('wallet.index', compact('wallet', 'deposits'));
    }

    public function create()
    {
        $methods = PaymentWallet::where('is_active', 1)->get();

        return view('wallet.deposit_create', compact('methods'));
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'payment_wallet_id'   => 'required|exists:payment_wallets,id',
    //         'amount'              => 'required|numeric|min:0.01',
    //         'transaction_reference' => 'nullable|string|max:255',
    //         'proof'               => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
    //     ]);

    //     $path = null;
    //     if ($request->hasFile('proof')) {
    //         $path = $request->file('proof')->store('wallet_proofs', 'public');
    //     }

    //     WalletDeposit::create([
    //         'user_id'             => Auth::id(),
    //         'payment_wallet_id'   => $request->payment_wallet_id,
    //         'amount'              => $request->amount,
    //         'currency'            => 'USD', // or from config / request
    //         'transaction_reference' => $request->transaction_reference,
    //         'proof_path'          => $path,
    //         'status'              => 'pending',
    //     ]);

    //     return redirect()
    //         ->route('wallet.index')
    //         ->with('success', 'Deposit submitted, waiting for admin approval.');
    // }



public function store(Request $request): JsonResponse
{
    // Match the form: wallet_id, amount, voucher
    $validator = Validator::make($request->all(), [
        'wallet_id'  => 'required|exists:payment_wallets,id',
        'amount'     => 'required|numeric|min:0.01',
        'voucher'    => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
        'transaction_reference' => 'nullable|string|max:255',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status'  => 'error',
            'message' => 'Validation failed.',
            'errors'  => $validator->errors(),
        ], 422);
    }

    $path = null;
    if ($request->hasFile('voucher')) {
        $path = $request->file('voucher')->store('wallet_proofs', 'public');
    }

    WalletDeposit::create([
        'user_id'             => Auth::id(),
        'payment_wallet_id'   => $request->wallet_id,   // from hidden input
        'amount'              => $request->amount,
        'currency'            => 'USDT',                // match your UI, or config
        'transaction_reference' => $request->transaction_reference,
        'proof_path'          => $path,
        'status'              => 'pending',
    ]);

    return response()->json([
        'status'  => 'success',
        'message' => 'Recharge proof submitted. Waiting for admin review.',
    ]);
}

}
