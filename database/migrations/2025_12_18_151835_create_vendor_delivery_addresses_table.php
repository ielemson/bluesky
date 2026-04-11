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
        Schema::create('vendor_delivery_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id'); // user/vendor
            $table->string('address', 255);
            $table->string('phone_country_code', 5)->default('+1');
            $table->string('phone_number', 30);
            $table->string('contact_name', 100);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            $table->foreign('vendor_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     *  php artisan migrate --path=database/migrations/2025_12_18_151835_create_vendor_delivery_addresses_table.php
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_delivery_addresses');
    }
};
