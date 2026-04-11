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
        Schema::create('order_items', function (Blueprint $table) {
       
        $table->id();

        $table->unsignedBigInteger('order_id');
        $table->unsignedBigInteger('product_id');
        $table->unsignedBigInteger('vendor_id');
        $table->unsignedBigInteger('vendor_product_id'); // your multi-vendor mapping

        $table->string('name');
        $table->decimal('price', 12, 2);
        $table->integer('quantity')->default(1);
        $table->decimal('total', 12, 2);

        $table->decimal('vendor_amount', 12, 2)->default(0); 
        // this is the amount that will go to vendor wallet (pending)

        $table->string('status')->default('pending'); 
        // pending, approved, rejected, refunded (admin decides)

        $table->timestamps();

        $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
    });
   
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
