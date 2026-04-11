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
        Schema::create('payout_wallet_options', function (Blueprint $table) {
        $table->id();
        $table->string('currency', 10);      // e.g. USDT
        $table->string('chain', 20);         // e.g. TRC-20
        $table->boolean('is_active')->default(true);
        $table->timestamps();
        });
    }

    /**
     * php artisan migrate --path=database/migrations/2025_12_16_180847_create_payout_wallet_options_table.php
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payout_wallet_options');
    }
};
