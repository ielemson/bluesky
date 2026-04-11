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
        Schema::table('products', function (Blueprint $table) {
            // Product Status Attributes
            $table->boolean('is_new_arrival')->default(false)->after('is_featured');
            $table->boolean('is_hot_selling')->default(false)->after('is_new_arrival');
            $table->boolean('is_best_seller')->default(false)->after('is_hot_selling');
            $table->boolean('is_trending')->default(false)->after('is_best_seller');
            $table->boolean('is_clearance')->default(false)->after('is_trending');
            $table->boolean('is_back_in_stock')->default(false)->after('is_clearance');
            $table->boolean('is_pre_order')->default(false)->after('is_back_in_stock');
            $table->boolean('is_flash_sale')->default(false)->after('is_pre_order');
            
            // Product Features
            $table->boolean('has_free_shipping')->default(false)->after('is_flash_sale');
            $table->boolean('is_eco_friendly')->default(false)->after('has_free_shipping');
            $table->boolean('is_sustainable')->default(false)->after('is_eco_friendly');
            $table->boolean('is_handmade')->default(false)->after('is_sustainable');
            $table->boolean('is_customizable')->default(false)->after('is_handmade');
            
            // Product Conditions
            $table->enum('condition', ['new', 'refurbished', 'used'])->default('new')->after('is_customizable');
            
            // Sale Information
            $table->timestamp('sale_start_date')->nullable()->after('condition');
            $table->timestamp('sale_end_date')->nullable()->after('sale_start_date');
            $table->integer('sale_percentage')->nullable()->after('sale_end_date');
            
            // Indexes for better performance
            $table->index('is_new_arrival');
            $table->index('is_hot_selling');
            $table->index('is_best_seller');
            $table->index('is_trending');
            $table->index('is_clearance');
            $table->index('is_flash_sale');
            $table->index('has_free_shipping');
            $table->index(['sale_start_date', 'sale_end_date']);
            $table->index(['is_new_arrival', 'is_featured']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop columns
            $table->dropColumn([
                'is_new_arrival',
                'is_hot_selling',
                'is_best_seller',
                'is_trending',
                'is_clearance',
                'is_back_in_stock',
                'is_pre_order',
                'is_flash_sale',
                'has_free_shipping',
                'is_eco_friendly',
                'is_sustainable',
                'is_handmade',
                'is_customizable',
                'condition',
                'sale_start_date',
                'sale_end_date',
                'sale_percentage',
            ]);

            // Drop indexes
            $table->dropIndex(['is_new_arrival']);
            $table->dropIndex(['is_hot_selling']);
            $table->dropIndex(['is_best_seller']);
            $table->dropIndex(['is_trending']);
            $table->dropIndex(['is_clearance']);
            $table->dropIndex(['is_flash_sale']);
            $table->dropIndex(['has_free_shipping']);
            $table->dropIndex(['sale_start_date', 'sale_end_date']);
            $table->dropIndex(['is_new_arrival', 'is_featured']);
        });
    }
};