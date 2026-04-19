<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalRequest;
use Illuminate\Http\Request;

class WithdrawalRecordController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $withdrawals = WithdrawalRequest::query()
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(15);

        return view('customer.withdrawals.index', compact('withdrawals'));
    }

    public function show(Request $request, WithdrawalRequest $withdrawal)
    {
        abort_unless((int) $withdrawal->user_id === (int) $request->user()->id, 403);

        return view('customer.withdrawals.show', compact('withdrawal'));
    }
}
