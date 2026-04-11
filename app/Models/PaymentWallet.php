<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentWallet extends Model
{
    protected $fillable = [
        'name',              // display name e.g. USDT TRC-20 Main
        'method',            // usdt, btc, eth
        'network',           // trc20, erc20, onchain, etc
        'deposit_address',   // wallet address / tag
        'min_amount',        // optional minimum
        'is_active',         // bool
        'is_primary',        // bool
        'qr_image_path',        // bool
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'is_primary' => 'boolean',
        'min_amount' => 'decimal:8',
    ];
    public function deposits()
{
    return $this->hasMany(WalletDeposit::class);
}

}
