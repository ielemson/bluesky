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
       Schema::create('orders', function (Blueprint $table) {
    $table->id();
    
    // Order Information
    $table->string('order_number')->unique();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
    
    // Customer Information
    $table->string('customer_name');
    $table->string('customer_email');
    $table->string('customer_phone')->nullable();
    $table->text('customer_address');
    $table->string('customer_city');
    $table->string('customer_state')->nullable();
    $table->string('customer_country');
    $table->string('customer_zipcode')->nullable();
    
    // Shipping Information
    $table->text('shipping_address')->nullable();
    $table->string('shipping_city')->nullable();
    $table->string('shipping_state')->nullable();
    $table->string('shipping_country')->nullable();
    $table->string('shipping_zipcode')->nullable();
    
    // Pricing
    $table->decimal('subtotal', 10, 2)->default(0);
    $table->decimal('shipping_cost', 10, 2)->default(0);
    $table->decimal('tax_amount', 10, 2)->default(0);
    $table->decimal('discount_amount', 10, 2)->default(0);
    $table->decimal('total_amount', 10, 2)->default(0);
    
    // Payment Information
    $table->string('payment_method')->default('cash');
    $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded', 'partially_refunded'])->default('pending');
    $table->string('payment_id')->nullable();
    $table->string('payment_gateway')->nullable();
    
    // Order Status
    $table->enum('order_status', ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded'])->default('pending');
    
    // Shipping Information
    $table->string('shipping_method')->nullable();
    $table->string('tracking_number')->nullable();
    $table->string('shipping_carrier')->nullable();
    
    // Additional Information
    $table->text('notes')->nullable();
    $table->text('cancellation_reason')->nullable();
    $table->decimal('refund_amount', 10, 2)->default(0);
    $table->text('refund_reason')->nullable();
    
    // Timestamps
    $table->timestamp('ordered_at')->nullable();
    $table->timestamp('paid_at')->nullable();
    $table->timestamp('shipped_at')->nullable();
    $table->timestamp('delivered_at')->nullable();
    $table->timestamp('cancelled_at')->nullable();
    $table->timestamp('refunded_at')->nullable();
    
    $table->timestamps();
    $table->softDeletes();
    
    // Indexes
    $table->index('order_number');
    $table->index('user_id');
    $table->index('vendor_id');
    $table->index('order_status');
    $table->index('payment_status');
    $table->index('created_at');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
