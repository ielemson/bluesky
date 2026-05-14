<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('vendor_invitation_codes')) {
            Schema::create('vendor_invitation_codes', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();

                $table->string('title')->nullable();
                $table->string('location')->nullable();
                $table->text('description')->nullable();

                $table->unsignedBigInteger('created_by')->nullable();

                $table->boolean('is_active')->default(true);
                $table->unsignedInteger('usage_limit')->nullable();
                $table->unsignedInteger('used_count')->default(0);

                $table->timestamp('expires_at')->nullable();
                $table->timestamps();

                $table->foreign('created_by')
                    ->references('id')
                    ->on('users')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_invitation_codes');
    }
};

// php artisan migrate --path=database/migrations/2026_05_12_161149_create_vendor_invitation_codes_table.php