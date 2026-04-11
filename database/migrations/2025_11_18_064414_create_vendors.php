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
          Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            
            // User relationship
            $table->unsignedBigInteger('user_id')->nullable();
            
            // Store information
            $table->string('store_logo')->nullable();
            $table->string('store_name');
            $table->string('store_slug')->unique();
            $table->text('store_description')->nullable();
            
            // Contact information
            $table->string('contact_person');
            $table->string('contact_email');
            $table->string('contact_phone')->nullable();
            $table->string('id_number');
            
            // Address information
            $table->text('address');
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('zip_code')->nullable();
            
            // Business information
            $table->string('main_business');
            $table->string('business_type')->nullable(); // individual, company
            $table->string('tax_number')->nullable(); // VAT, GST, etc.
            $table->string('website')->nullable();
            
            // Verification documents
            $table->string('idcard_front');
            $table->string('idcard_back');
            $table->string('business_license')->nullable(); // For companies
            $table->string('tax_certificate')->nullable(); // For companies
            
            // Invitation system
            $table->string('invite_code')->nullable();
            $table->unsignedBigInteger('referred_by')->nullable(); // Referring vendor
            
            // Bank information (for payouts)
            $table->string('bank_name')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_swift_code')->nullable();
            $table->string('bank_iban')->nullable();
            
            // Status and approval
            $table->enum('status', ['pending', 'approved', 'rejected', 'suspended'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->text('suspension_reason')->nullable();
            
            // Vendor metrics
            $table->decimal('rating', 3, 2)->default(0.00); // Average rating
            $table->integer('total_ratings')->default(0); // Number of ratings
            $table->integer('total_products')->default(0); // Number of products in store
            $table->integer('total_orders')->default(0); // Total orders received
            $table->integer('completed_orders')->default(0); // Completed orders
            $table->decimal('total_earnings', 12, 2)->default(0.00); // Total earnings
            $table->decimal('pending_balance', 12, 2)->default(0.00); // Balance awaiting payout
            $table->decimal('paid_balance', 12, 2)->default(0.00); // Total paid out
            
            // Commission settings
            $table->decimal('commission_rate', 5, 2)->default(0.00); // Platform commission percentage
            $table->enum('commission_type', ['percentage', 'fixed'])->default('percentage');
            
            // Settings
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->boolean('accept_returns')->default(true);
            $table->integer('return_period_days')->default(7); // Return policy period
            
            // Timestamps
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('suspended_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('profile_completed_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('user_id');
            $table->index('store_slug');
            $table->index('status');
            $table->index('is_featured');
            $table->index('is_verified');
            $table->index('created_at');
            $table->index(['status', 'is_featured']);
            
            // Foreign key constraints
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
                  
            $table->foreign('referred_by')
                  ->references('id')
                  ->on('vendors')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
