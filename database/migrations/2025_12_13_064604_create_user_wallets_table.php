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
        Schema::create('user_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Total money in the wallet (including held / pending)
            $table->decimal('account_balance', 12, 2)->default(0);

            // Spendable money (account_balance - held/pending)
            $table->decimal('available_balance', 12, 2)->default(0);

            // Optional: track held funds separately (for withdrawals, orders, etc.)
            $table->decimal('on_hold', 12, 2)->default(0);

            $table->timestamps();
        });
    }

    /**php artisan migrate --path=database/migrations/2025_12_13_064604_create_user_wallets_table.php
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_wallets');
    }
};
