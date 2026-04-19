<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Models\UserMessage;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'nickname',
        'email',
        'password',
        'contact',
        'customer_id',
        'is_vendor',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Laravel 10+ recommended casting style
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_vendor' => 'boolean',
        ];
    }

    /*
|--------------------------------------------------------------------------
| Relationships
|--------------------------------------------------------------------------
*/

    public function vendor()
    {
        return $this->hasOne(Vendor::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function wallet()
    {
        return $this->hasOne(UserWallet::class);
    }

    public function payoutWallet()
    {
        return $this->hasOne(UserPayoutWallet::class);
    }

    // public function payoutWallets()
    // {
    //     return $this->hasMany(UserPayoutWallet::class);
    // }

    public function payoutWallets()
    {
        return $this->hasMany(\App\Models\UserPayoutWallet::class);
    }


    public function defaultWallet()
    {
        return $this->hasOne(UserWallet::class)->where('currency', 'USD');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(UserMessage::class);
    }

    public function unreadMessages(): HasMany
    {
        return $this->hasMany(UserMessage::class)->where('is_read', false);
    }
    /**
     * NOTE: this name suggests "user delivery addresses", but model is VendorDeliveryAddress.
     * If these are actually user addresses, consider renaming the model/table/foreign key.
     */
    public function deliveryAddresses()
    {
        return $this->hasMany(VendorDeliveryAddress::class, 'user_id'); // confirm FK is correct
    }

    public function recharges()
    {
        return $this->hasMany(UserRecharge::class, 'user_id');
    }
    public function paymentWallet()
    {
        return $this->belongsTo(PaymentWallet::class, 'payment_wallet_id');
    }
    /*
|--------------------------------------------------------------------------
| Scopes
|--------------------------------------------------------------------------
*/

    public function scopeVendors(Builder $query): Builder
    {
        return $query->where('is_vendor', true);
    }

    public function scopeCustomers(Builder $query): Builder
    {
        return $query->where('is_vendor', false);
    }

    public function scopeVerified(Builder $query): Builder
    {
        return $query->whereNotNull('email_verified_at');
    }

    /*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

    public function isVendor(): bool
    {
        return (bool) $this->is_vendor;
    }

    public function isCustomer(): bool
    {
        return ! $this->isVendor();
    }

    public function hasVendorApplication(): bool
    {
        // If your "application" is different from the Vendor profile, rename this method.
        return $this->vendor()->exists();
    }

    /*
|--------------------------------------------------------------------------
| Accessors
|--------------------------------------------------------------------------
*/

    public function getDisplayNameAttribute(): string
    {
        return (string) ($this->nickname ?: $this->name);
    }

    /**
     * Return plain status info (avoid HTML in models).
     * Use these in Blade to build badges.
     */
    public function getVerificationStatusAttribute(): string
    {
        return $this->email_verified_at ? 'verified' : 'unverified';
    }

    public function getUserTypeAttribute(): string
    {
        return $this->isVendor() ? 'vendor' : 'customer';
    }

    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    /**
     * Safer email masking:
     * - keeps first 2–4 chars (depending on length)
     * - handles weird/invalid emails gracefully
     */
    public function maskedEmail(): string
    {
        $email = (string) $this->email;

        if (! str_contains($email, '@')) {
            return $email; // fallback for invalid values
        }

        [$name, $domain] = explode('@', $email, 2);

        $keep = min(4, max(2, strlen($name))); // keep 2..4 chars
        $visible = substr($name, 0, $keep);

        return $visible . str_repeat('*', 4) . '@' . $domain;
    }
}
