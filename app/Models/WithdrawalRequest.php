<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawalRequest extends Model
{
    protected $fillable = [
        'user_id',
        'user_payout_wallet_id',
        'payout_wallet_option_id',
        'method_type',
        'amount',
        'fee',
        'net_amount',
        'request_currency',

        'bank_name',
        'bank_code',
        'account_name',
        'account_number',
        'bank_branch',

        'crypto_currency',
        'crypto_chain',
        'wallet_address',
        'wallet_tag_memo',

        'option_currency',
        'option_chain',

        'note',
        'status',
        'reviewed_at',
        'reviewed_by',
        'admin_remark',
        'approved_at',
        'paid_at',
        'cancelled_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'fee' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'reviewed_at' => 'datetime',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function userPayoutWallet()
    {
        return $this->belongsTo(UserPayoutWallet::class);
    }

    public function payoutWalletOption()
    {
        return $this->belongsTo(PayoutWalletOption::class);
    }
}