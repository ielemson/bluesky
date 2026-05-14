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
        'vendor_invitation_code_id',
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
        'rejection_reason',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function vendorProducts()
    {
        return $this->hasMany(VendorProduct::class, 'vendor_id');
    }

  public function invitationCode()
{
    return $this->belongsTo(
        VendorInvitationCode::class,
        'invite_code',
        'code'
    );
}

    public function products()
    {
        return $this->hasManyThrough(
            Product::class,
            VendorProduct::class,
            'vendor_id',
            'id',
            'id',
            'product_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
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

    public function scopeActive($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }
}