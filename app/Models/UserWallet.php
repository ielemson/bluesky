<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserWallet extends Model
{
    protected $fillable = [
        'user_id',
        'account_balance',
        'available_balance',
        'on_hold',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Optional helpers
    public function credit(float $amount): void
    {
        $this->increment('account_balance', $amount);
        $this->increment('available_balance', $amount);
        $this->refresh();
    }

    public function hold(float $amount): bool
    {
        if ($this->available_balance < $amount) {
            return false;
        }

        $this->decrement('available_balance', $amount);
        $this->increment('on_hold', $amount);
        $this->refresh();

        return true;
    }

    public function releaseHold(float $amount): bool
    {
        if ($this->on_hold < $amount) {
            return false;
        }

        $this->decrement('on_hold', $amount);
        $this->increment('available_balance', $amount);
        $this->refresh();

        return true;
    }
}
