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

        $table->string('order_number')->unique();

        $table->unsignedBigInteger('user_id')->nullable(); // guest checkout allowed
        $table->string('customer_name');
        $table->string('customer_email')->nullable();
        $table->string('customer_phone')->nullable();
        $table->text('customer_address')->nullable();
        $table->string('customer_city')->nullable();
        $table->string('customer_state')->nullable();
        $table->string('customer_country')->nullable();
        $table->string('customer_zipcode')->nullable();

        // Shipping information (can be different)
        $table->text('shipping_address')->nullable();
        $table->string('shipping_city')->nullable();
        $table->string('shipping_state')->nullable();
        $table->string('shipping_country')->nullable();
        $table->string('shipping_zipcode')->nullable();

        // Money
        $table->decimal('subtotal', 12, 2)->default(0);
        $table->decimal('shipping_cost', 12, 2)->default(0);
        $table->decimal('tax_amount', 12, 2)->default(0);
        $table->decimal('discount_amount', 12, 2)->default(0);
        $table->decimal('total_amount', 12, 2)->default(0);

        // Payment
        $table->string('payment_method')->nullable();  // bitcoin, etc.
        $table->string('payment_status')->default('pending'); // pending, paid, failed, refunded
        $table->string('payment_id')->nullable();
        $table->string('payment_gateway')->nullable();

        // Order flow
        $table->string('order_status')->default('pending'); // pending, processing, shipped, delivered, cancelled

        // Shipping details
        $table->string('shipping_method')->nullable();
        $table->string('tracking_number')->nullable();
        $table->string('shipping_carrier')->nullable();

        // Notes
        $table->text('notes')->nullable();
        $table->text('cancellation_reason')->nullable();

        // Refund
        $table->decimal('refund_amount', 12, 2)->nullable();
        $table->text('refund_reason')->nullable();

        // Timestamps for the lifecycle
        $table->timestamp('ordered_at')->nullable();
        $table->timestamp('paid_at')->nullable();
        $table->timestamp('shipped_at')->nullable();
        $table->timestamp('delivered_at')->nullable();
        $table->timestamp('cancelled_at')->nullable();
        $table->timestamp('refunded_at')->nullable();

        $table->softDeletes(); 
        $table->timestamps();
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
