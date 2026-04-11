<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'vendor_id')) {
                $table->foreignId('vendor_id')
                    ->nullable()
                    ->constrained()
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('orders', 'created_by')) {
                $table->foreignId('created_by')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('orders', 'order_source')) {
                $table->string('order_source')
                    ->default('admin');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'vendor_id')) {
                try {
                    $table->dropForeign(['vendor_id']);
                } catch (\Throwable $e) {
                }
                $table->dropColumn('vendor_id');
            }

            if (Schema::hasColumn('orders', 'created_by')) {
                try {
                    $table->dropForeign(['created_by']);
                } catch (\Throwable $e) {
                }
                $table->dropColumn('created_by');
            }

            if (Schema::hasColumn('orders', 'order_source')) {
                $table->dropColumn('order_source');
            }
        });
    }
};