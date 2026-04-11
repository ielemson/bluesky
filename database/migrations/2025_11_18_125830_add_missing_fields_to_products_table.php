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
        $table->string('meta_keywords')->nullable();
        $table->string('brand')->nullable();
        $table->string('model')->nullable();
        $table->string('warranty')->nullable();
        $table->boolean('allow_backorder')->default(0);
        $table->boolean('is_virtual')->default(0);
        $table->boolean('is_downloadable')->default(0);
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('products', function (Blueprint $table) {
        $table->dropColumn([
            'meta_keywords',
            'brand',
            'model',
            'warranty',
            'allow_backorder',
            'is_virtual',
            'is_downloadable',
        ]);
    });
    }
};
