<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payout_wallet_options', function (Blueprint $table) {
            // Add new columns
            $table->string('type')->default('crypto')->after('id'); // crypto | bank
            $table->string('display_name')->nullable()->after('type');
        });

        // Optional: Backfill existing records
        DB::table('payout_wallet_options')
            ->whereNull('display_name')
            ->update([
                'display_name' => DB::raw("CONCAT(currency, ' - ', chain)")
            ]);
    }

    public function down(): void
    {
        Schema::table('payout_wallet_options', function (Blueprint $table) {
            $table->dropColumn(['type', 'display_name']);
        });
    }
};

// php artisan migrate --path=database/migrations/2026_04_13_164017_alter_payout_wallet_options_table_add_type_and_display_name.php
