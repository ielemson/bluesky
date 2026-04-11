<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletDeposit extends Model
{
    protected $fillable = [
        'user_id',
        'payment_wallet_id',
        'amount',
        'currency',
        'transaction_reference',
        'proof_path',
        'status',
        'admin_note',
        'reviewed_at',
        'reviewed_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentWallet()
    {
        return $this->belongsTo(PaymentWallet::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
