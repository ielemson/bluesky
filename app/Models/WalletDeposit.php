<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

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

    protected $casts = [
        'amount' => 'decimal:2',
        'reviewed_at' => 'datetime',
    ];

    const STATUS_PENDING  = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function paymentWallet(): BelongsTo
    {
        return $this->belongsTo(PaymentWallet::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function approve(int $adminId): void
    {
        if ($this->isApproved() && $this->reviewed_at) {
            return;
        }

        DB::transaction(function () use ($adminId) {
            $currency = strtoupper($this->currency);

            $wallet = UserWallet::firstOrCreate(
                [
                    'user_id' => $this->user_id,
                    'currency' => $currency,
                ],
                [
                    'balance' => 0,
                ]
            );

            $wallet->credit((float) $this->amount);

            $this->update([
                'currency' => $currency,
                'status' => self::STATUS_APPROVED,
                'reviewed_by' => $adminId,
                'reviewed_at' => now(),
            ]);
        });
    }

    public function reject(int $adminId, ?string $note = null): void
    {
        if ($this->isApproved()) {
            throw new \RuntimeException('Cannot reject an approved deposit.');
        }

        $this->update([
            'status' => self::STATUS_REJECTED,
            'admin_note' => $note,
            'reviewed_by' => $adminId,
            'reviewed_at' => now(),
        ]);
    }
}