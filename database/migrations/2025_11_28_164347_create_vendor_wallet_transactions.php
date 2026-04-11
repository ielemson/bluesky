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
        Schema::create('vendor_wallet_transactions', function (Blueprint $table) {
          $table->id();

        $table->unsignedBigInteger('vendor_id');
        $table->unsignedBigInteger('order_id')->nullable();
        $table->unsignedBigInteger('order_item_id')->nullable();

        $table->enum('type', ['credit', 'debit']);
        $table->decimal('amount', 12, 2);

        $table->string('status')->default('pending');
        // pending → admin approves → completed
        // rejected → returned to system

        $table->text('description')->nullable();
        $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_wallet_transactions');
    }
};
