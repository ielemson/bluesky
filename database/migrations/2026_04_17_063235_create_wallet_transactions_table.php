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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('direction', ['credit', 'debit']);

            $table->string('category', 50);
            // Examples:
            // deposit, withdrawal, admin_topup, admin_debit,
            // order_payment, refund, fee, adjustment

            $table->decimal('amount', 12, 2);

            $table->decimal('balance_before', 12, 2)->default(0);
            $table->decimal('balance_after', 12, 2)->default(0);

            $table->string('reference')->nullable()->unique();

            $table->string('source_type')->nullable();
            // Examples: WalletDeposit, WithdrawalRequest, Order, AdminAdjustment

            $table->unsignedBigInteger('source_id')->nullable();

            $table->string('status', 30)->default('completed');
            // pending, completed, failed, reversed, cancelled

            $table->text('description')->nullable();

            $table->json('meta')->nullable();

            $table->timestamp('posted_at')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['user_id', 'category']);
            $table->index(['user_id', 'direction']);
            $table->index(['source_type', 'source_id']);
            $table->index('posted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};

// php artisan migrate --path=database/migrations/2026_04_17_063235_create_wallet_transactions_table.php