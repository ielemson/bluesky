<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PayoutWalletOption;
use Illuminate\Http\Request;

class PayoutWalletOptionController extends Controller
{
    public function index()
    {
        $options = PayoutWalletOption::query()
            ->orderBy('currency')
            ->orderBy('chain')
            ->latest('id')
            ->get();

        return view('admin.payout.index', compact('options'));
    }

    public function create()
    {
        return view('admin.payout.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type'      => ['required', 'in:crypto,bank'],
            'currency'  => ['nullable', 'string', 'max:20'],
            'chain'     => ['nullable', 'string', 'max:50'],
            'note'      => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($data['type'] === 'bank') {
            $data['currency'] = 'NGN';
            $data['chain'] = 'BANK_TRANSFER';
        } else {
            $request->validate([
                'currency' => ['required', 'string', 'max:20'],
                'chain'    => ['required', 'string', 'max:50'],
            ]);

            $data['currency'] = strtoupper(trim($data['currency']));
            $data['chain'] = strtoupper(trim($data['chain']));
        }

        $data['note'] = $request->filled('note') ? trim($request->note) : null;
        $data['is_active'] = $request->boolean('is_active');

        $exists = PayoutWalletOption::where('currency', $data['currency'])
            ->where('chain', $data['chain'])
            ->exists();

        if ($exists) {
            return back()
                ->withErrors([
                    'currency' => 'This payout option already exists.',
                ])
                ->withInput();
        }

        unset($data['type']);

        PayoutWalletOption::create($data);

        return redirect()
            ->route('admin.wallet-options.index')
            ->with('status', 'Payout wallet option created.');
    }

    public function edit(PayoutWalletOption $option)
    {
        return view('admin.payout.edit', compact('option'));
    }

    public function update(Request $request, PayoutWalletOption $option)
    {
        $data = $request->validate([
            'type'      => ['required', 'in:crypto,bank'],
            'currency'  => ['nullable', 'string', 'max:20'],
            'chain'     => ['nullable', 'string', 'max:50'],
            'note'      => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($data['type'] === 'bank') {
            $data['currency'] = 'NGN';
            $data['chain'] = 'BANK_TRANSFER';
        } else {
            $request->validate([
                'currency' => ['required', 'string', 'max:20'],
                'chain'    => ['required', 'string', 'max:50'],
            ]);

            $data['currency'] = strtoupper(trim($data['currency']));
            $data['chain'] = strtoupper(trim($data['chain']));
        }

        $data['note'] = $request->filled('note') ? trim($request->note) : null;
        $data['is_active'] = $request->boolean('is_active');

        $exists = PayoutWalletOption::where('currency', $data['currency'])
            ->where('chain', $data['chain'])
            ->where('id', '!=', $option->id)
            ->exists();

        if ($exists) {
            return back()
                ->withErrors([
                    'currency' => 'This payout option already exists.',
                ])
                ->withInput();
        }

        unset($data['type']);

        $option->update($data);

        return redirect()
            ->route('admin.wallet-options.index')
            ->with('status', 'Payout wallet option updated.');
    }

    public function destroy(PayoutWalletOption $option)
    {
        $option->delete();

        return back()->with('status', 'Payout wallet option deleted.');
    }
}