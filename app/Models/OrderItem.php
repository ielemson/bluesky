<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'vendor_id',
        'vendor_product_id',
        'name',
        'price',
        'quantity',
        'total',
        'vendor_amount',
        'status',
    ];

    protected $casts = [
        'price' => 'float',
        'total' => 'float',
        'vendor_amount' => 'float',
    ];

    // ---- STATUS CONSTANTS ----
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_REFUNDED = 'refunded';

    // ---- RELATIONSHIPS ----

    /**
     * Parent order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Vendor/shop linked to this item
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Exact vendor stock row used for this item
     */
    public function vendorProduct()
    {
        return $this->belongsTo(VendorProduct::class, 'vendor_product_id');
    }

    /**
     * Master product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}