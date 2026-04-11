<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PayoutWalletOption;
use Illuminate\Http\Request;

class PayoutWalletOptionController extends Controller
{
    public function index()
    {
        $options = PayoutWalletOption::orderBy('currency')->orderBy('chain')->get();

        return view("admin.payout.index", compact('options'));
    }

    public function create()
    {
        return view("admin.payout.create");
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'currency' => ['required', 'string', 'max:10'],
            'chain'    => ['required', 'string', 'max:20'],
            'is_active'=> ['boolean'],
        ]);

        PayoutWalletOption::create($data);

        

        return redirect()->route('admin.wallet-options.index')
            ->with('status', 'Payout wallet option created.');
    }

    public function edit(PayoutWalletOption $option)
    {
        return view('admin.payout.edit', compact('option'));
    }

    public function update(Request $request, PayoutWalletOption $option)
    {
        $data = $request->validate([
            'currency' => ['required', 'string', 'max:10'],
            'chain'    => ['required', 'string', 'max:20'],
            'is_active'=> ['boolean'],
        ]);

        $option->update($data);

        return redirect()->route('admin.wallet-options.index')
            ->with('status', 'Payout wallet option updated.');
    }

    public function destroy(PayoutWalletOption $option)
    {
        $option->delete();

        return back()->with('status', 'Payout wallet option deleted.');
    }
}
