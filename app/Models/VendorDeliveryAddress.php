<?php

// app/Models/VendorDeliveryAddress.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorDeliveryAddress extends Model
{
    protected $fillable = [
        'vendor_id',
        'address',
        'phone_country_code',
        'phone_number',
        'contact_name',
        'is_default',
    ];

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }
}
