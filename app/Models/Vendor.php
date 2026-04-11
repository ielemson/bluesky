<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $table = 'vendors';

    protected $fillable = [
        'user_id',
        'store_logo',
        'store_name',
        'contact_person',
        'id_number',
        'invite_code',
        'idcard_front',
        'idcard_back',
        'main_business',
        'address',
        'status',
    ];

    /*
|--------------------------------------------------------------------------
| RELATIONSHIPS
|--------------------------------------------------------------------------
*/
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
|--------------------------------------------------------------------------
| SCOPES
|--------------------------------------------------------------------------
*/
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // In Vendor.php
public function scopeActive($query)
{
    return $query->where('status', 'approved');
}

public function vendorProducts()
{
    return $this->hasMany(VendorProduct::class, 'vendor_id');
}

public function products()
{
    // convenient shortcut to get Product models directly if you ever need it
    return $this->hasManyThrough(
        Product::class,
        VendorProduct::class,
        'vendor_id',   // FK on vendor_products
        'id',          // PK on products
        'id',          // PK on vendors
        'product_id'   // FK on vendor_products
    );
}
}
