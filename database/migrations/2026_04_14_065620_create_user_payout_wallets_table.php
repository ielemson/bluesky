<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_payout_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payout_wallet_option_id')->constrained('payout_wallet_options')->cascadeOnDelete();
            $table->string('wallet_address');
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            $table->unique(['user_id', 'payout_wallet_option_id', 'wallet_address'], 'user_wallet_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_payout_wallets');
    }
};

// php artisan migrate --path=database/migrations/2026_04_14_065620_create_user_payout_wallets_table.php