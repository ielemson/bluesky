<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PayoutWalletOption;
use App\Models\UserPayoutWallet;

class VendorpayoutWalletController extends Controller
{
    //  public function index(Request $request)
    // {
    //     $user   = $request->user();
    //     $wallets = $user->payoutWallets()->with('option')->get(); // hasMany
    //     $options = PayoutWalletOption::where('is_active', true)
    //         ->orderBy('currency')->orderBy('chain')->get();

    //     return view("customer.wallet.wallet-list", compact('wallets', 'options'));
    // }

    // public function store(Request $request)
    // {
    //     $data = $request->validate([
    //         'payout_wallet_option_id' => ['required', 'exists:payout_wallet_options,id'],
    //         'address'                 => ['required', 'string', 'max:255'],
    //     ]);

    //     $wallet = UserPayoutWallet::create([
    //         'user_id'                => $request->user()->id,
    //         'payout_wallet_option_id'=> $data['payout_wallet_option_id'],
    //         'address'                => $data['address'],
    //     ]);

    //     return response()->json([
    //         'status'  => 'ok',
    //         'message' => 'Payout wallet added successfully.',
    //         'wallet'  => $wallet->load('option'),
    //     ]);
    // }

    public function index(Request $request)
    {
        $user     = $request->user();
        $wallets  = $user->payoutWallets()->with('option')->get();
        $options  = PayoutWalletOption::where('is_active', true)
            ->orderBy('currency')->orderBy('chain')->get();

        return view("customer.wallet.wallet-list", compact('wallets', 'options'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'payout_wallet_option_id' => ['required', 'exists:payout_wallet_options,id'],
            'address'                 => ['required', 'string', 'max:255'],
        ]);

        $wallet = UserPayoutWallet::create([
            'user_id'                => $request->user()->id,
            'payout_wallet_option_id'=> $data['payout_wallet_option_id'],
            'address'                => $data['address'],
        ]);

        return response()->json([
            'status'  => 'ok',
            'message' => 'Payout wallet added successfully.',
            'wallet'  => $wallet->load('option'),
        ]);
    }

    public function update(Request $request, UserPayoutWallet $wallet)
    {
        $this->authorize('update', $wallet); // optional

        $data = $request->validate([
            'payout_wallet_option_id' => ['required', 'exists:payout_wallet_options,id'],
            'address'                 => ['required', 'string', 'max:255'],
        ]);

        $wallet->update($data);

        return response()->json([
            'status'  => 'ok',
            'message' => 'Payout wallet updated successfully.',
            'wallet'  => $wallet->load('option'),
        ]);
    }

    public function destroy(Request $request, UserPayoutWallet $wallet)
    {
        $this->authorize('delete', $wallet); // optional

        $wallet->delete();

        return response()->json([
            'status'  => 'ok',
            'message' => 'Payout wallet deleted successfully.',
        ]);
    }
}
