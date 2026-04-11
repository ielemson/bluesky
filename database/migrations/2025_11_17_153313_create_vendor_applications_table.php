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
        Schema::create('vendor_applications', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id')->nullable();
        $table->string('store_logo');
        $table->string('store_name');
        $table->string('contact_person');
        $table->string('id_number');
        $table->string('invite_code')->nullable();
        $table->string('idcard_front');
        $table->string('idcard_back');
        $table->string('main_business');
        $table->text('address');
        $table->enum('status', ['pending','approved','rejected'])->default('pending');
        $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_applications');
    }
};
