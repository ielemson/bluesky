<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('vendors', 'vendor_invitation_code_id')) {
            Schema::table('vendors', function (Blueprint $table) {
                $table->unsignedBigInteger('vendor_invitation_code_id')
                    ->nullable()
                    ->after('user_id');
            });
        }

        Schema::table('vendors', function (Blueprint $table) {
            $table->foreign('vendor_invitation_code_id')
                ->references('id')
                ->on('vendor_invitation_codes')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropForeign(['vendor_invitation_code_id']);
            $table->dropColumn('vendor_invitation_code_id');
        });
    }
};
// php artisan migrate --path=database/migrations/2026_05_12_161159_add_invitation_code_to_vendors_table.php