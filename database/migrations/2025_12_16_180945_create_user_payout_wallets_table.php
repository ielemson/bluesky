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
        Schema::create('user_payout_wallets', function (Blueprint $table) {
           $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payout_wallet_option_id')
                ->constrained('payout_wallet_options')
                ->cascadeOnDelete();
            $table->string('address');
            $table->timestamps();
        });
    }

    /**
     * php artisan migrate --path=database/migrations/2025_12_16_180945_create_user_payout_wallets_table.php
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_payout_wallets');
    }
};
