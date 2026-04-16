<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('title');
            $table->text('message');
            $table->string('type')->nullable(); // wallet, withdrawal, order, system

            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();

            $table->json('meta')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'is_read']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_messages');
    }
};
// php artisan migrate --path=database/migrations/2026_04_16_053858_create_user_messages_table.php