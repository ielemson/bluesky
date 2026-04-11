<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentWallet;
use App\Models\WalletDeposit;
use App\Models\UserWallet;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
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
}
