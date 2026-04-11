<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Schema::create('vendor_products', function (Blueprint $table) {
            $table->id();
            
            // Relationships
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('product_id');
            
            // Vendor-specific pricing and inventory
            $table->decimal('vendor_price', 10, 2)->nullable();
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->integer('min_stock_alert')->default(5);
            $table->integer('max_purchase_qty')->nullable(); // Maximum quantity per order
            $table->integer('min_purchase_qty')->default(1); // Minimum quantity per order
            
            // Shipping
            $table->decimal('shipping_cost', 8, 2)->default(0.00);
            $table->boolean('free_shipping')->default(false);
            $table->integer('handling_days')->default(1); // Days to process order
            
            // Status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_approved')->default(true); // Admin approval for vendor products
            $table->text('rejection_reason')->nullable(); // If admin rejects
            
            // Sales metrics
            $table->integer('view_count')->default(0);
            $table->integer('sold_count')->default(0);
            $table->decimal('total_revenue', 12, 2)->default(0.00);
            
            // Timestamps
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            // Unique constraint - vendor can only add a product once
            $table->unique(['vendor_id', 'product_id']);
            
            // Indexes
            $table->index('vendor_id');
            $table->index('product_id');
            $table->index('is_active');
            $table->index('is_approved');
            $table->index(['vendor_id', 'is_active']);
            $table->index(['product_id', 'is_active']);
            
            // Foreign keys
            $table->foreign('vendor_id')
                  ->references('id')
                  ->on('vendors')
                  ->onDelete('cascade');
                  
            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_products');
    }
};
