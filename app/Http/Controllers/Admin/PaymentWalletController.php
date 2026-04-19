<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            'qr_image'        => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data['is_active']  = $request->boolean('is_active');
        $data['is_primary'] = $request->boolean('is_primary');
        $data['method']     = strtoupper(trim($data['method']));
        $data['network']    = !empty($data['network']) ? strtoupper(trim($data['network'])) : null;

        if ($request->hasFile('qr_image')) {
            $path = $request->file('qr_image')->store('wallet_qr', 'public');
            $data['qr_image_path'] = 'storage/' . $path;
        }

        unset($data['qr_image']);

        DB::transaction(function () use ($data) {
            if ($data['is_primary']) {
                PaymentWallet::where('method', $data['method'])->update(['is_primary' => false]);
            }

            PaymentWallet::create($data);
        });

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
        $data['method']     = strtoupper(trim($data['method']));
        $data['network']    = !empty($data['network']) ? strtoupper(trim($data['network'])) : null;

        DB::transaction(function () use ($request, $wallet, $data) {
            if ($data['is_primary']) {
                PaymentWallet::where('method', $data['method'])
                    ->where('id', '!=', $wallet->id)
                    ->update(['is_primary' => false]);
            }

            if ($request->hasFile('qr_image')) {
                // Delete old file if exists
                if ($wallet->qr_image_path && str_starts_with($wallet->qr_image_path, 'storage/')) {
                    $oldPath = str_replace('storage/', '', $wallet->qr_image_path);
                    Storage::disk('public')->delete($oldPath);
                }

                $path = $request->file('qr_image')->store('wallet_qr', 'public');
                $data['qr_image_path'] = 'storage/' . $path;
            }

            $wallet->update($data);
        });

        return redirect()
            ->route('admin.wallets.index')
            ->with('success', 'Wallet updated successfully.');
    }

    public function destroy(PaymentWallet $wallet)
    {
        DB::transaction(function () use ($wallet) {
            // Delete QR image file if exists
            if ($wallet->qr_image_path && str_starts_with($wallet->qr_image_path, 'storage/')) {
                $oldPath = str_replace('storage/', '', $wallet->qr_image_path);
                Storage::disk('public')->delete($oldPath);
            }

            $wallet->delete();
        });

        return redirect()
            ->route('admin.wallets.index')
            ->with('success', 'Wallet deleted successfully.');
    }
}