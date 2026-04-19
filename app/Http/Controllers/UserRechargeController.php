<?php

namespace App\Http\Controllers;

use App\Models\PaymentWallet;
use App\Models\WalletDeposit;
use App\Models\UserRecharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserRechargeController extends Controller
{
 public function index(Request $request)
    {
        $user = $request->user();

        $recharges = WalletDeposit::where('user_id', $user->id)
            ->latest()
            ->paginate(15);

        return view('customer.recharge.index', compact('recharges'));
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'payment_wallet_id' => ['required', 'integer'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'currency' => ['required', 'string', 'max:20'],
            'transaction_reference' => ['nullable', 'string', 'max:191'],
            'proof_path' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,webp', 'max:4096'],
        ]);

        $proofPath = null;

        if ($request->hasFile('proof_path')) {
            $proofPath = $request->file('proof_path')->store('wallet-deposits', 'public');
        }

        WalletDeposit::create([
            'user_id' => $user->id,
            'payment_wallet_id' => $data['payment_wallet_id'],
            'amount' => $data['amount'],
            'currency' => $data['currency'],
            'transaction_reference' => $data['transaction_reference'] ?? null,
            'proof_path' => $proofPath,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('customer.recharges.index')
            ->with('status', 'Recharge submitted successfully.');
    }

    public function show(Request $request, WalletDeposit $recharge)
    {
        abort_unless($recharge->user_id === $request->user()->id, 403);

        return view('customer.recharge.show', compact('recharge'));
    }

    public function destroy(Request $request, WalletDeposit $recharge)
    {
        abort_unless($recharge->user_id === $request->user()->id, 403);

        if ($recharge->status !== 'pending') {
            return back()->withErrors([
                'recharge' => 'Only pending recharge records can be deleted.',
            ]);
        }

        if ($recharge->proof_path && Storage::disk('public')->exists($recharge->proof_path)) {
            Storage::disk('public')->delete($recharge->proof_path);
        }

        $recharge->delete();

        return redirect()
            ->route('customer.recharges.index')
            ->with('status', 'Recharge record deleted successfully.');
    }
}
