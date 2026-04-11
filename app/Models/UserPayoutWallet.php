<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPayoutWallet extends Model
{
    protected $fillable = ['user_id', 'payout_wallet_option_id', 'address'];

    public function option()
    {
        return $this->belongsTo(PayoutWalletOption::class, 'payout_wallet_option_id');
    }
}
