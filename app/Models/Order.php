<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_number',
        'user_id',
        'vendor_id',
        'created_by',
        'order_source',

        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'customer_city',
        'customer_state',
        'customer_country',
        'customer_zipcode',

        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_country',
        'shipping_zipcode',

        'subtotal',
        'shipping_cost',
        'tax_amount',
        'discount_amount',
        'total_amount',

        'payment_method',
        'payment_status',
        'payment_id',
        'payment_gateway',

        'order_status',
        'shipping_method',
        'tracking_number',
        'shipping_carrier',

        'notes',
        'cancellation_reason',
        'refund_amount',
        'refund_reason',

        'ordered_at',
        'paid_at',
        'shipped_at',
        'delivered_at',
        'cancelled_at',
        'refunded_at',

        // scheduled order fields
        'order_date',
        'scheduled_for',
        'released_at',
        'is_scheduled',
    ];

    protected $casts = [
        'subtotal' => 'float',
        'shipping_cost' => 'float',
        'tax_amount' => 'float',
        'discount_amount' => 'float',
        'total_amount' => 'float',
        'refund_amount' => 'float',

        'order_date' => 'date',
        'ordered_at' => 'datetime',
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'refunded_at' => 'datetime',

        'scheduled_for' => 'datetime',
        'released_at' => 'datetime',
        'is_scheduled' => 'boolean',
    ];

    // ---- PAYMENT STATUS CONSTANTS ----
    const PAYMENT_PENDING = 'pending';
    const PAYMENT_PAID = 'paid';
    const PAYMENT_FAILED = 'failed';
    const PAYMENT_REFUNDED = 'refunded';

    // ---- ORDER STATUS CONSTANTS ----
    const ORDER_PENDING = 'pending';
    const ORDER_SCHEDULED = 'scheduled';
    const ORDER_PROCESSING = 'processing';
    const ORDER_SHIPPED = 'shipped';
    const ORDER_DELIVERED = 'delivered';
    const ORDER_COMPLETED = 'completed';
    const ORDER_CANCELLED = 'cancelled';
    const ORDER_REFUNDED = 'refunded';

    // ---- ORDER SOURCE CONSTANTS ----
    const SOURCE_FRONTEND = 'frontend';
    const SOURCE_ADMIN = 'admin';
    const SOURCE_VENDOR = 'vendor';
    const SOURCE_API = 'api';

    // ---- RELATIONSHIPS ----

    /**
     * Order Items
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Customer placing the order
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Vendor/shop this order belongs to
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Admin/staff who created the order
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ---- HELPERS / SCOPES ----

    public function scopeReleased($query)
    {
        return $query->where(function ($q) {
            $q->where('is_scheduled', false)
              ->orWhere(function ($qq) {
                  $qq->whereNotNull('scheduled_for')
                     ->where('scheduled_for', '<=', now());
              });
        });
    }

    public function scopeScheduled($query)
    {
        return $query->where('order_status', self::ORDER_SCHEDULED);
    }

    public function isScheduled(): bool
    {
        return (bool) $this->is_scheduled;
    }

    public function isReleased(): bool
    {
        if (!$this->is_scheduled) {
            return true;
        }

        return !is_null($this->scheduled_for) && $this->scheduled_for <= now();
    }
}