<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // When order should be released to vendor
            $table->timestamp('scheduled_for')->nullable()->after('ordered_at');

            // When it was actually released
            $table->timestamp('released_at')->nullable()->after('scheduled_for');

            // Optional helper flag
            $table->boolean('is_scheduled')->default(false)->after('order_status');

            // Index for scheduler performance
            $table->index(['order_status', 'scheduled_for']);
        });

        // Modify ENUM to include 'scheduled' (if ENUM is used)
        DB::statement("
            ALTER TABLE orders 
            MODIFY order_status ENUM(
                'pending',
                'scheduled',
                'processing',
                'completed',
                'cancelled'
            ) DEFAULT 'pending'
        ");
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['order_status', 'scheduled_for']);
            $table->dropColumn([
                'scheduled_for',
                'released_at',
                'is_scheduled',
            ]);
        });

        // Revert ENUM (adjust to your original values)
        DB::statement("
            ALTER TABLE orders 
            MODIFY order_status ENUM(
                'pending',
                'processing',
                'completed',
                'cancelled'
            ) DEFAULT 'pending'
        ");
    }
};
// php artisan migrate --path=database/migrations/2026_04_10_032634_add_scheduling_fields_to_orders_table.php
