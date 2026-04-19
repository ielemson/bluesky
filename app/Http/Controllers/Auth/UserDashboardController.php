<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentWallet;
use App\Models\WalletDeposit;
use App\Models\UserWallet;
use App\Models\PayoutWalletOption;
use App\Models\UserPayoutWallet;
use Auth;
use Illuminate\Support\Facades\Hash;
class UserDashboardController extends Controller
{
    

public function dashboard()
{
    return view('customer.dashboard');
}

public function paymentMethods()
{
    $wallets = PaymentWallet::where('is_active', true)
        ->orderBy('method')
        ->orderBy('network')
        ->get(['id','name','method','network','deposit_address','qr_image_path','is_primary']);

    return response()->json($wallets);
}

public function vendorbalance()
{
    $userId = Auth::id();

    $wallet = UserWallet::firstOrCreate(
        ['user_id' => $userId],
        ['account_balance' => 0, 'available_balance' => 0, 'on_hold' => 0]
    );

    $pendingDeposits = WalletDeposit::where('user_id', $userId)
        ->where('status', 'pending')
        ->orderByDesc('created_at')
        ->get();

    $historyDeposits = WalletDeposit::where('user_id', $userId)
        ->whereIn('status', ['approved', 'rejected'])
        ->orderByDesc('created_at')
        ->get();

    return view('customer.wallet.balance', compact('wallet', 'pendingDeposits', 'historyDeposits'));
}

 public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'current_password'       => ['required'],
            'password'               => ['required', 'string', 'min:6', 'max:20', 'confirmed'],
        ]);

        if (! Hash::check($data['current_password'], $user->password)) {
            return response()->json([
                'status'  => 'error',
                'message' => gtrans('Original password is incorrect.'),
                'errors'  => ['current_password' => [gtrans('Original password is incorrect.')]],
            ], 422);
        }

        $user->forceFill([
            'password' => bcrypt($data['password']),
        ])->save();

        return response()->json([
            'status'  => 'ok',
            'message' => gtrans('Password updated successfully.'),
        ]);
    }

    public function profile()
{
    $user = Auth::user()->load([
        'vendor',
    ]);

    return view('customer.profile', compact('user'));
}


// public function withdrawalMethods(Request $request)
// {
//     $methods = collect([
//         [
//             'id' => 'bank_transfer',
//             'name' => 'Online Banking Withdrawal',
//             'type' => 'bank',
//             'currency' => null,
//             'chain' => null,
//         ]
//     ]);

//     $cryptoOptions = \App\Models\PayoutWalletOption::query()
//         ->where('is_active', true)
//         ->orderBy('currency')
//         ->orderBy('chain')
//         ->get()
//         ->map(function ($option) {
//             return [
//                 'id' => (string) $option->id,
//                 'name' => $option->currency . '-' . $option->chain,
//                 'type' => 'crypto',
//                 'currency' => $option->currency,
//                 'chain' => $option->chain,
//             ];
//         });

//     return response()->json(
//         $methods->merge($cryptoOptions)->values()
//     );
// }

public function withdrawalMethods(Request $request)
{
    $methods = \App\Models\PayoutWalletOption::query()
        ->where('is_active', true)
        ->orderBy('currency')
        ->orderBy('chain')
        ->get()
        ->map(function ($option) {
            $type = $option->type ?? 'crypto';

            return [
                'id' => (string) $option->id,
                'name' => $option->name ?: (
                    $type === 'bank'
                        ? 'Online Banking Withdrawal'
                        : trim(($option->currency ?? '') . '-' . ($option->chain ?? ''), '-')
                ),
                'type' => $type,
                'currency' => $option->currency,
                'chain' => $option->chain,
            ];
        })
        ->values();

    return response()->json($methods);
}

    public function withdrawalAddresses($option)
    {
        $user = Auth::user();

        // Bank method does not need wallet addresses
        if ((string) $option === 'bank_transfer') {
            return response()->json([]);
        }

        $addresses = UserPayoutWallet::query()
            ->where('user_id', $user->id)
            ->where('payout_wallet_option_id', $option)
            ->orderByDesc('is_default')
            ->orderByDesc('id')
            ->get(['id', 'wallet_address', 'is_default'])
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'wallet_address' => $item->wallet_address,
                    'is_default' => (bool) $item->is_default,
                    'display' => $item->is_default
                        ? 'Default - ' . $item->wallet_address
                        : $item->wallet_address,
                ];
            })
            ->values();

        return response()->json($addresses);
    }
}
