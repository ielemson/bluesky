<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorInvitationCode extends Model
{
    protected $fillable = [
        'code',
        'title',
        'location',
        'description',
        'created_by',
        'is_active',
        'usage_limit',
        'used_count',
        'expires_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function vendors()
    {
        return $this->hasMany(Vendor::class, 'vendor_invitation_code_id');
    }

    public function isUsable(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->expires_at && now()->greaterThan($this->expires_at)) {
            return false;
        }

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }
}