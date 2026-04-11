<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentWalletController extends Controller
{
    public function index()
    {
        $wallets = PaymentWallet::orderBy('method')->orderBy('network')->get();
        return view('admin.wallets.index', compact('wallets'));
    }

    public function create()
    {
        return view('admin.wallets.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:255',
            'method'          => 'required|string|max:50',
            'network'         => 'nullable|string|max:50',
            'deposit_address' => 'required|string|max:255',
            'min_amount'      => 'nullable|numeric|min:0',
            'is_active'       => 'nullable|boolean',
            'is_primary'      => 'nullable|boolean',
            'qr_image'        => 'nullable|image|max:2048', // file input name
        ]);

        $data['is_active']  = $request->boolean('is_active');
        $data['is_primary'] = $request->boolean('is_primary');

        if ($data['is_primary']) {
            PaymentWallet::where('method', $data['method'])->update(['is_primary' => false]);
        }

        if ($request->hasFile('qr_image')) {
            $path = $request->file('qr_image')->store('wallet_qr', 'public');
            $data['qr_image_path'] = 'storage/' . $path; // DB field
        }

        PaymentWallet::create($data);

        return redirect()
            ->route('admin.wallets.index')
            ->with('success', 'Wallet created successfully.');
    }

    public function edit(PaymentWallet $wallet)
    {
        return view('admin.wallets.edit', compact('wallet'));
    }

    public function update(Request $request, PaymentWallet $wallet)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:255',
            'method'          => 'required|string|max:50',
            'network'         => 'nullable|string|max:50',
            'deposit_address' => 'required|string|max:255',
            'min_amount'      => 'nullable|numeric|min:0',
            'is_active'       => 'nullable|boolean',
            'is_primary'      => 'nullable|boolean',
            'qr_image'        => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data['is_active']  = $request->boolean('is_active');
        $data['is_primary'] = $request->boolean('is_primary');

        if ($data['is_primary']) {
            PaymentWallet::where('method', $data['method'])
                ->where('id', '!=', $wallet->id)
                ->update(['is_primary' => false]);
        }

        if ($request->hasFile('qr_image')) {
            // delete old file if exists
            if ($wallet->qr_image_path && str_starts_with($wallet->qr_image_path, 'storage/')) {
                $old = str_replace('storage/', '', $wallet->qr_image_path);
                Storage::disk('public')->delete($old);
            }

            $path = $request->file('qr_image')->store('wallet_qr', 'public');
            $data['qr_image_path'] = 'storage/' . $path; // DB field
        }

        $wallet->update($data);

        return redirect()
            ->route('admin.wallets.index')
            ->with('success', 'Wallet updated successfully.');
    }

    public function destroy(PaymentWallet $wallet)
    {
        if ($wallet->qr_image_path && str_starts_with($wallet->qr_image_path, 'storage/')) {
            $old = str_replace('storage/', '', $wallet->qr_image_path);
            Storage::disk('public')->delete($old);
        }

        $wallet->delete();

        return redirect()
            ->route('admin.wallets.index')
            ->with('success', 'Wallet deleted successfully.');
    }
}
