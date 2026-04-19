<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRecharge extends Model
{
    use HasFactory;

    protected $table = 'wallet_deposits';

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

    protected $casts = [
        'amount' => 'decimal:2',
        'reviewed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function paymentWallet()
    {
        return $this->belongsTo(PaymentWallet::class, 'payment_wallet_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}