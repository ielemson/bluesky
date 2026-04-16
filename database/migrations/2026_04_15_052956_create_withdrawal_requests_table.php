
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('withdrawal_requests', function (Blueprint $table) {
            $table->id();

            // Owner
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Optional saved wallet / configured payout option
            $table->foreignId('user_payout_wallet_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('payout_wallet_option_id')->nullable()->constrained()->nullOnDelete();

            // Method type
            $table->enum('method_type', ['online_banking', 'crypto']);

            // Financials
            $table->decimal('amount', 15, 2);
            $table->decimal('fee', 15, 2)->default(0);
            $table->decimal('net_amount', 15, 2);
            $table->string('request_currency', 20)->default('NGN');

            /**
             * Online banking fields
             */
            $table->string('bank_name')->nullable();
            $table->string('bank_code')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('bank_branch')->nullable();

            /**
             * Crypto fields
             */
            $table->string('crypto_currency', 20)->nullable();
            $table->string('crypto_chain', 30)->nullable();
            $table->text('wallet_address')->nullable();
            $table->string('wallet_tag_memo')->nullable();

            /**
             * Snapshot of selected option at request time
             */
            $table->string('option_currency', 20)->nullable();
            $table->string('option_chain', 30)->nullable();

            // User note
            $table->text('note')->nullable();

            // Status
            $table->enum('status', ['pending', 'approved', 'rejected', 'paid', 'cancelled'])
                ->default('pending');

            // Admin review
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('admin_remark')->nullable();

            // Processing timestamps
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['method_type', 'status']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawal_requests');
    }
};

// php artisan migrate --path=database/migrations/2026_04_15_052956_create_withdrawal_requests_table.php