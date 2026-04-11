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
        Schema::create('payment_wallets', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('method');            // usdt, btc, eth
        $table->string('network')->nullable(); // TRC-20, ERC-20 etc.
        $table->string('deposit_address');
        $table->string('qr_image_path')->nullable();
        $table->decimal('min_amount', 18, 8)->nullable();
        $table->boolean('is_active')->default(true);
        $table->boolean('is_primary')->default(false);
        $table->timestamps();
        });
    }
// php artisan migrate --path=database/migrations/2025_12_12_162543_create_payment_wallets_table.php
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_wallets');
    }
};
