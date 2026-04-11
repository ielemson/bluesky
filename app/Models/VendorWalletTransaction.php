<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorWalletTransaction extends Model
{
    protected $fillable = [
        'vendor_id',
        'order_id',
        'order_item_id',

        'amount',
        'type',        // credit, debit
        'status',      // pending, approved, rejected

        'notes',
    ];

    protected $casts = [
        'amount' => 'float'
    ];

    const TYPE_CREDIT = 'credit';
    const TYPE_DEBIT = 'debit';

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    // ---- RELATIONSHIPS ----

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}
