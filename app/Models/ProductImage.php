<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image_path',
        'is_primary',
        'sort_order',
        'alt_text'
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Scopes
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    public function scopeSorted($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    // Helpers
    public function getImageUrlAttribute()
    {
        return $this->image_path ? asset('storage/' . $this->image_path) : null;
    }

    public function getThumbnailUrlAttribute()
    {
        // You can implement different image sizes here
        return $this->image_url;
    }

    // Events
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($productImage) {
            // If this is set as primary, remove primary from other images of the same product
            if ($productImage->is_primary) {
                static::where('product_id', $productImage->product_id)
                    ->where('is_primary', true)
                    ->update(['is_primary' => false]);
            }
        });

        static::updating(function ($productImage) {
            // If this is set as primary, remove primary from other images of the same product
            if ($productImage->is_primary) {
                static::where('product_id', $productImage->product_id)
                    ->where('id', '!=', $productImage->id)
                    ->where('is_primary', true)
                    ->update(['is_primary' => false]);
            }
        });

        static::deleting(function ($productImage) {
            // If deleting a primary image, set another image as primary
            if ($productImage->is_primary) {
                $newPrimary = static::where('product_id', $productImage->product_id)
                    ->where('id', '!=', $productImage->id)
                    ->sorted()
                    ->first();

                if ($newPrimary) {
                    $newPrimary->update(['is_primary' => true]);
                }
            }
        });
    }
}