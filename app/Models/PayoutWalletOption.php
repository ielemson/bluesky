<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayoutWalletOption extends Model
{
   protected $fillable = [
        'type',
        'display_name',
        'currency',
        'chain',
        'is_active'
    ];

    // public function userWallets()
    // {
    //     return $this->hasMany(UserPayoutWallet::class);
    // }

    //  public function userWallets()
    // {
    //     return $this->hasMany(UserPayoutWallet::class, 'payout_wallet_option_id');
    // }

      public function userWallets()
    {
        return $this->hasMany(UserPayoutWallet::class);
    }
    
    public function withdrawalRequests()
{
    return $this->hasMany(WithdrawalRequest::class);
}

}