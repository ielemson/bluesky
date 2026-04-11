<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayoutWalletOption extends Model
{
    protected $fillable = ['currency', 'chain', 'is_active'];

    public function userWallets()
    {
        return $this->hasMany(UserPayoutWallet::class);
    }
}