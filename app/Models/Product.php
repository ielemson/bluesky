<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Mass Assignment
     */
    protected $fillable = [
        'name','slug','description','short_description',
        'price','compare_price','cost_price',
        'stock_quantity','sku','barcode','track_quantity','low_stock_threshold',
        'weight','length','width','height',
        'category_id','status','is_featured','is_available_for_vendors','published_at',
        'meta_title','meta_description','meta_keywords',
        'brand','model','warranty',
        'is_new_arrival','is_hot_selling','is_best_seller','is_trending',
        'is_clearance','is_back_in_stock','is_pre_order','is_flash_sale',
        'has_free_shipping','is_eco_friendly','is_sustainable','is_handmade','is_customizable',
        'condition','sale_start_date','sale_end_date','sale_percentage',
        'is_virtual','is_downloadable','allow_backorder',
    ];

    /**
     * Casting
     */
    protected $casts = [
        'price' => 'decimal:2', 'compare_price' => 'decimal:2', 'cost_price' => 'decimal:2',
        'weight' => 'decimal:2', 'length' => 'decimal:2', 'width' => 'decimal:2', 'height' => 'decimal:2',

        'is_featured' => 'boolean', 'is_available_for_vendors' => 'boolean', 'track_quantity' => 'boolean',
        'is_virtual' => 'boolean', 'is_downloadable' => 'boolean', 'allow_backorder' => 'boolean',

        'is_new_arrival' => 'boolean', 'is_hot_selling' => 'boolean', 'is_best_seller' => 'boolean',
        'is_trending' => 'boolean', 'is_clearance' => 'boolean', 'is_back_in_stock' => 'boolean',
        'is_pre_order' => 'boolean', 'is_flash_sale' => 'boolean', 'has_free_shipping' => 'boolean',
        'is_eco_friendly' => 'boolean', 'is_sustainable' => 'boolean', 'is_handmade' => 'boolean',
        'is_customizable' => 'boolean',

        'published_at' => 'datetime', 'sale_start_date' => 'datetime', 'sale_end_date' => 'datetime',
    ];

    /*=============================================
    | Relationships
    =============================================*/

    /** Category */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /** Product Images */
    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order')->orderBy('id');
    }

    /*=============================================
    | Vendor Relationships
    =============================================*/

    /** All vendor-product records */
    public function vendorProducts()
    {
        return $this->hasMany(VendorProduct::class, 'product_id');
    }

    /** Active vendor listings only */
    public function activeVendorListings()
    {
        return $this->hasMany(VendorProduct::class, 'product_id')
                    ->where('is_active', true);
    }

    /** All vendor listings including inactive */
    public function vendorListings()
    {
        return $this->hasMany(VendorProduct::class, 'product_id');
    }

    /** Active vendors – used for withCount() */
    public function activeVendors()
    {
        return $this->hasMany(VendorProduct::class, 'product_id')
                    ->where('is_active', 1);
    }

    /** Vendor pivot */
    public function vendors()
    {
        return $this->belongsToMany(Vendor::class, 'vendor_products')
                    ->withPivot('vendor_price', 'stock_quantity', 'is_active')
                    ->withTimestamps();
    }

    /*=============================================
    | Query Scopes
    =============================================*/

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                     ->where('published_at', '<=', now());
    }

    public function scopeAvailableForVendors($query)
    {
        return $query->where('is_available_for_vendors', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeWithVendor($query, $vendorId)
    {
        return $query->whereHas('vendors', fn($q) => $q->where('vendor_id', $vendorId));
    }

    /** Attribute-based scopes */
    public function scopeNewArrival($q)     { return $q->where('is_new_arrival', true); }
    public function scopeHotSelling($q)     { return $q->where('is_hot_selling', true); }
    public function scopeBestSeller($q)     { return $q->where('is_best_seller', true); }
    public function scopeTrending($q)       { return $q->where('is_trending', true); }
    public function scopeClearance($q)      { return $q->where('is_clearance', true); }
    public function scopeBackInStock($q)    { return $q->where('is_back_in_stock', true); }
    public function scopePreOrder($q)       { return $q->where('is_pre_order', true); }
    public function scopeEcoFriendly($q)    { return $q->where('is_eco_friendly', true); }
    public function scopeSustainable($q)    { return $q->where('is_sustainable', true); }
    public function scopeHandmade($q)       { return $q->where('is_handmade', true); }
    public function scopeCustomizable($q)   { return $q->where('is_customizable', true); }
    public function scopeFreeShipping($q)   { return $q->where('has_free_shipping', true); }

    public function scopeFlashSale($query)
    {
        return $query->where('is_flash_sale', true)
                     ->where(fn($q) => 
                        $q->whereNull('sale_end_date')->orWhere('sale_end_date', '>', now())
                     );
    }

    public function scopeOnSale($query)
    {
        return $query->whereNotNull('sale_percentage')
            ->where(fn($q) =>
                $q->whereNull('sale_start_date')->orWhere('sale_start_date', '<=', now())
            )
            ->where(fn($q) =>
                $q->whereNull('sale_end_date')->orWhere('sale_end_date', '>', now())
            );
    }

    /*=============================================
    | Accessors & Helpers
    =============================================*/

    public function getPrimaryImageAttribute()
    {
        return $this->images->where('is_primary', true)->first()
            ?? $this->images->first();
    }

    // public function getPrimaryImageUrlAttribute()
    // {
    //     return $this->primaryImage
    //         ? asset('storage/' . $this->primaryImage->image_path)
    //         : asset('images/default-product.png');
    // }

    public function getHasDiscountAttribute()
    {
        return $this->compare_price && $this->compare_price > $this->price;
    }

    public function getDiscountPercentageAttribute()
    {
        return $this->has_discount
            ? round(($this->compare_price - $this->price) / $this->compare_price * 100)
            : 0;
    }

    public function getIsOnSaleAttribute()
    {
        if (!$this->sale_percentage) return false;

        $now = now();
        return (!$this->sale_start_date || $this->sale_start_date <= $now)
            && (!$this->sale_end_date || $this->sale_end_date > $now);
    }

    public function getSalePriceAttribute()
    {
        return $this->is_on_sale
            ? $this->price - ($this->price * $this->sale_percentage / 100)
            : $this->price;
    }

    public function getSaleEndsInAttribute()
    {
        return ($this->sale_end_date && $this->is_on_sale)
            ? $this->sale_end_date->diffForHumans()
            : null;
    }

    public function getConditionLabelAttribute()
    {
        return match ($this->condition) {
            'new' => 'New',
            'refurbished' => 'Refurbished',
            'used' => 'Used',
            default => 'New'
        };
    }

    public function getProductBadgesAttribute()
    {
        $badges = [];

        $map = [
            'is_new_arrival'   => ['New Arrival', 'badge-primary'],
            'is_hot_selling'   => ['Hot Selling', 'badge-danger'],
            'is_best_seller'   => ['Best Seller', 'badge-success'],
            'is_trending'      => ['Trending', 'badge-info'],
            'is_clearance'     => ['Clearance', 'badge-warning'],
            'is_back_in_stock' => ['Back in Stock', 'badge-secondary'],
            'is_pre_order'     => ['Pre-Order', 'badge-dark'],
            'has_free_shipping'=> ['Free Shipping', 'badge-success'],
            'is_eco_friendly'  => ['Eco Friendly', 'badge-success'],
            'is_sustainable'   => ['Sustainable', 'badge-info'],
            'is_handmade'      => ['Handmade', 'badge-warning'],
            'is_customizable'  => ['Customizable', 'badge-primary'],
        ];

        foreach ($map as $key => [$label, $class]) {
            if ($this->$key) {
                $badges[] = compact('label', 'class');
            }
        }

        if ($this->is_flash_sale && $this->is_on_sale) {
            $badges[] = ['label' => 'Flash Sale', 'class' => 'badge-danger'];
        }

        return $badges;
    }

    /*=============================================
    | Vendor Helper Methods
    =============================================*/

    public function isAddedByVendor($vendorId)
    {
        return $this->vendors()->where('vendor_id', $vendorId)->exists();
    }

    public function getVendorProduct($vendorId)
    {
        return $this->vendorProducts()->where('vendor_id', $vendorId)->first();
    }

    public function getVendorPrice($vendorId)
    {
        return optional($this->getVendorProduct($vendorId))->vendor_price
            ?? $this->price;
    }

    /*=============================================
    | Image Management
    =============================================*/

    public function addImage($path, $isPrimary = false, $sortOrder = null)
    {
        if ($isPrimary) {
            $this->images()->update(['is_primary' => false]);
        }

        $sortOrder ??= $this->images()->count();

        return $this->images()->create([
            'image_path' => $path,
            'is_primary' => $isPrimary,
            'sort_order' => $sortOrder,
            'alt_text'   => $this->name,
        ]);
    }

    public function setPrimaryImage($imageId)
    {
        $this->images()->update(['is_primary' => false]);
        return $this->images()->findOrFail($imageId)
                              ->update(['is_primary' => true]);
    }

    public function reorderImages(array $imageIds)
    {
        foreach ($imageIds as $index => $id) {
            $this->images()
                ->where('id', $id)
                ->update(['sort_order' => $index]);
        }
    }

    /*=============================================
    | Model Events
    =============================================*/

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if ($product->is_new_arrival === null) {
                $product->is_new_arrival = true;
            }
        });

        static::updating(function ($product) {
            if ($product->stock_quantity > 0 &&
                $product->getOriginal('stock_quantity') == 0) {
                $product->is_back_in_stock = true;
            }
        });
    }

    public function getPrimaryImageUrlAttribute()
{
    if (!$this->primaryImage || !$this->primaryImage->image_path) {
        return asset('images/default-product.png');
    }

    $path = $this->primaryImage->image_path;

    if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])) {
        return $path;
    }

    if (\Illuminate\Support\Str::startsWith($path, 'storage/')) {
        return asset($path);
    }

    return asset('storage/' . ltrim($path, '/'));
}
}
