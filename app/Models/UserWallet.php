<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserWallet extends Model
{
    protected $fillable = [
        'user_id',
        'currency',
        'balance',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function credit(float $amount): void
    {
        $this->increment('balance', $amount);
        $this->refresh();
    }

    public function debit(float $amount): void
    {
        if ((float) $this->balance < $amount) {
            throw new \RuntimeException('Insufficient wallet balance.');
        }

        $this->decrement('balance', $amount);
        $this->refresh();
    }
}