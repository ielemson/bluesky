<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPayoutWallet extends Model
{
    protected $fillable = [
        'user_id',
        'payout_wallet_option_id',
        'wallet_address',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function walletOption()
    {
        return $this->belongsTo(PayoutWalletOption::class, 'payout_wallet_option_id');
    }
}