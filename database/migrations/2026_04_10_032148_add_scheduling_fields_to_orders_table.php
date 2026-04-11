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
        Schema::table('orders', function (Blueprint $table) {
            // When the order should become visible to vendor
            $table->timestamp('scheduled_for')->nullable()->after('order_date');

            // When the order was actually released to vendor
            $table->timestamp('released_at')->nullable()->after('scheduled_for');

            // Order lifecycle status
            $table->enum('status', [
                'draft',
                'scheduled',
                'released',
                'processing',
                'completed',
                'cancelled'
            ])->default('draft')->change();

            // Optional: flag for clarity (not strictly needed if using status)
            $table->boolean('is_scheduled')->default(false)->after('status');

            // Index for performance (VERY IMPORTANT for scheduler)
            $table->index(['status', 'scheduled_for']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['status', 'scheduled_for']);
            $table->dropColumn([
                'scheduled_for',
                'released_at',
                'is_scheduled',
            ]);
        });
    }
};