<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorProduct extends Model
{
    protected $table = 'vendor_products';

   protected $casts = [
    'vendor_price' => 'decimal:2',
    'stock_quantity' => 'integer',
    'vendor_id' => 'integer',
    'product_id' => 'integer',
    'is_active' => 'boolean',
];

    protected $fillable = [
        'vendor_id',
        'product_id',
        'vendor_price',
        'stock_quantity',
        'is_active'
    ];

   public function vendor()
{
    return $this->belongsTo(Vendor::class, 'vendor_id');
}


    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
